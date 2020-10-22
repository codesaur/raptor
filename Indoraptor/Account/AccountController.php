<?php namespace Indoraptor\Account;

use codesaur as single;
use codesaur\HTML\Template;

use Indoraptor\Content\MailerModel;
use Indoraptor\Content\ContentModel;

use PHPMailer\PHPMailer\PHPMailer;

class AccountController extends \Indoraptor\IndoController
{
    public function signup()
    {
        $response = array();
        try {
            $payload = $this->payload();
            if ($this->isEmpty($payload->email ?? null) || $this->isEmpty($payload->username ?? null) ||
                    $this->isEmpty($payload->password ?? null) || $this->isEmpty($payload->flag ?? null)) {
                throw new \Exception('payload');
            }
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE email=:eml");
            $stmt->bindParam(':eml', $payload->email, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                throw new \Exception('email');
            }            
            $pstmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE username=:usr");
            $pstmt->bindParam(':usr', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('username')->getLength());
            $pstmt->execute();
            if ($pstmt->rowCount() == 1) {
                throw new \Exception('username');
            }
            
            $mailer = $this->getMailer();
            if ( ! $mailer) {
                throw new \Exception('mailer');
            }
            
            $content = new ContentModel($this->conn);
            $content->setTables('templates');
            $templates = $content->getByKeyword('request-new-account');
            if ( ! isset($templates['full'][$payload->flag]) ||
                    ! isset($templates['title'][$payload->flag])) {
                throw new \Exception('template');
            }
            
            $model->setTable('newbie');
            $id = $model->insert(array(
                'email' => $payload->email,
                'username' => $payload->username,
                'password' => $payload->password,
                'code' => $payload->flag));
            if ( ! $id) {
                throw new \Exception('newbie');
            }
            
            $response['id'] = $id;
            
            $template = new Template();
            $template->set('email', $payload->email);
            $template->set('username', $payload->username);
            $template->source($templates['full'][$payload->flag]);
            
            $mailer->MsgHTML($template->output());
            $mailer->Subject = $templates['title'][$payload->flag];
            $mailer->AddAddress($payload->email, $payload->username);
            $mailer->Send();
        } catch(\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            $response['error'] = $e->getMessage();
        } finally {
            $this->success($response);
        }
    }
    
    public function forgot()
    {
        $response = array();
        try {
            $payload = $this->payload();
            $flag = $payload->flag ?? 'en';            
            if ( ! isset($payload->email)) {
                throw new \Exception('invalid');
            }
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()}  WHERE email=:eml");
            $stmt->bindParam(':eml', $payload->email, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() != 1) {
                throw new \Exception('account');
            }
            
            $record = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (((int) $record['is_active']) == 0) {
                throw new \Exception('inactive');
            }
            
            $mailer = $this->getMailer();
            if ( ! $mailer) {
                throw new \Exception('mailer');
            }
            
            $content = new ContentModel($this->conn);
            $content->setTables('templates');
            $templates = $content->getByKeyword('forgotten-password-reset');
            if ( ! isset($templates['full'][$flag]) ||
                    ! isset($templates['title'][$flag])) {
                throw new \Exception('template');
            }

            $useid = \uniqid('use');                        
            $forgot = new ForgotModel($this->conn);
            $response['forgot'] = array(
                'flag'       => $flag,
                'use_id'     => $useid,
                'account'    => $record['id'],
                'email'      => $record['email'],
                'username'   => $record['username'],
                'last_name'  => $record['last_name'],
                'first_name' => $record['first_name']
            );

            if ( ! $forgot->insert($response['forgot'])) {
                throw new \Exception('forgot');
            }
            
            $template = new Template();
            $template->set('email', $payload->email);
            $template->source($templates['full'][$flag]);
            $login_link = $payload->login ?? (single::app()->getWebUrl(false) . '/dashboard/login');
            $template->set('link', "$login_link?forgot=$useid");

            $mailer->MsgHTML($template->output());
            $mailer->Subject = $templates['title'][$flag];
            $mailer->AddAddress($payload->email, $record['first_name'] . ' ' . \mb_substr($record['last_name'], 0, 1, 'UTF-8'));
            $mailer->Send();
        } catch(\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            $response['error'] = $e->getMessage();
        } finally {
            $this->success($response);
        }
    }
    
    public function getForgot()
    {
        $payload = $this->payload();
        
        if (isset($payload->use)) {
            $model = new ForgotModel($this->conn);
            $record = $model->getBy('use_id', $payload->use);

            if (((int) $record['is_active']) == 1) {
                return $this->success($record);
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function setPassword()
    {
        $payload = $this->payload();
        if ( ! isset($payload->use_id) || empty($payload->use_id)
                || ! isset($payload->account) || empty($payload->account)
                || ! isset($payload->password) || empty($payload->password)
                || ! isset($payload->created_at) || empty($payload->created_at)) {
            return $this->error('Invalid request');
        }
        
        $forgot = new ForgotModel($this->conn);
        $recordf = $forgot->getBy('use_id', $payload->use_id);

        if ( ! $recordf
                || $payload->account != $recordf['account']
                || $payload->created_at != $recordf['created_at']) {
            return $this->error('Invalid payload');
        }
        
        $account = new AccountModel($this->conn);
        $recorda = $account->getByID($payload->account);
        if ( ! $recorda) {
            return $this->error('Invalid account');
        }
        
        $result = $account->update(array(
            'id' => (int) $payload->account,
            'password' => $payload->password
        ));

        if ( ! $result) {
            return $this->error('Not Found');
        }
        
        $forgot->deleteByID((int) $recordf['id']);

        unset($recorda['password']);
        unset($recorda['created_at']);
        unset($recorda['created_by']);
        unset($recorda['updated_at']);
        unset($recorda['updated_by']);            

        $this->success($recorda);
    }
    
    public function getMailer() : ?PHPMailer
    {
        $model = new MailerModel($this->conn);
        $rows = $model->getRows();
        
        return single::helper()->getPHPMailer(\end($rows));        
    }
}
