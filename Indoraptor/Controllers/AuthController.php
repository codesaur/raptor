<?php namespace Indoraptor\Controllers;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\HTML\Template;

use Indoraptor\Models\Forgot;
use Indoraptor\Models\Accounts;
use Indoraptor\Models\Contents;
use Indoraptor\Models\Organizations;
use Indoraptor\Models\OrganizationUsers;

class AuthController extends IndoController
{
    public function entry()
    {
        $this->connect();
        
        try {
            $payload = $this->payload();
            if ( ! isset($payload->username) || empty($payload->username) ||
                    ! isset($payload->password) || empty($payload->password)) {
                throw new \Exception('invalid request');
            }
            
            $model = new Accounts($this->conn);
            $stmt = $model->dataobject()->prepare('SELECT * FROM accounts WHERE username=:usr OR email=:eml LIMIT 1');
            $stmt->bindParam(':eml', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->bindParam(':usr', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('username')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() != 1) {
                throw new \Exception('account not found');
            }

            $account = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ( ! (new Post())->asPassword($payload->password, $account['password'])) {
                throw new \Exception('invalid password');
            }            
            if (((int) $account['status']) == 0) {
                throw new \Exception('inactive user');
            }
            
            unset($account['password']);
            
            $login = array('account_id' => $account['id']);
            
            $last = $this->getLastLoginOrg($account['id']);
            if ($last != null) {
                $login['organization_id'] = $last;
            }
            
            $account['jwt'] = $this->generate($login);
            
            return $this->success(array('account' => $account));
        } catch(\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    public function jwt()
    {
        $this->connect();
        
        try {
            $payload = $this->payload();
            if ( ! isset($payload->jwt)) {
                throw new \Exception('Please provide information!');
            }

            $validation = $this->validate($payload->jwt);
            if ( ! isset($validation['account_id'])) {
                throw new \Exception('Invalid JWT - Authentication failed!');
            }

            $model = new Accounts($this->conn);
            $account = $model->getByID($validation['account_id']);
            if ( ! isset($account['id'])) {
                throw new \Exception('Account not found!');
            }

            unset($account['password']);

            $response = array('account' => $account);

            $org_model = new Organizations($this->conn);
            $organization = $org_model->getByID($validation['organization_id'] ?? 1);
            if ( ! isset($organization['id'])) {
                throw new \Exception('Account does not belong to an organization!');
            }
            
            $user_model = new OrganizationUsers($this->conn);

            $response['organizations'] = $this->getAccountOrganizations($account['id']);

            if ( ! empty($response['organizations'])) {
                if (\count($response['organizations']) == 1) {
                    if ($response['organizations'][0]['id'] != $organization['id']) {
                        $organization = $org_model->getByID((int) $response['organizations'][0]['id']);
                    }
                }
            }

            $response['organization'] = $organization;

            $user = $user_model->retrieve($organization['id'], $account['id']);
            if ( ! isset($user['id'])) {
                throw new \Exception('Can\'t get organization user information!');
            } else {
                $response['organization']['user'] = $user;
            }

            return $this->success($response);
        } catch(\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    public function getLastLoginOrg($account_id)
    {
        if ($this->connect(false)) {
            $last_org_query =
                'SELECT info ' .
                'FROM dashboard_log ' .
                "WHERE created_by = :id AND reason = 'organization' AND level = 2 " .
                'ORDER By id Desc ' .
                'LIMIT 1';

            $stmt = $this->conn->prepare($last_org_query);
            $stmt->bindParam(':id', $account_id, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                if (isset($result['info'])) {
                    $info = \json_decode($result['info'], true);

                    $org_id = $info['enter']['id'] ?? null;
                    $account_id = $info['jwt-info']['account_id'] ?? null;

                    if (isset($org_id) && isset($account_id)) {
                        $org_user_query =
                                'SELECT * ' .
                                'FROM organization_users ' .
                                "WHERE organization_id = :org AND account_id = :account AND status = 1 AND is_active = 1 " .
                                'ORDER By id Desc ' .
                                'LIMIT 1';
                        $stmt = $this->conn->prepare($org_user_query);
                        $stmt->bindParam(':org', $org_id, \PDO::PARAM_INT);
                        $stmt->bindParam(':account', $account_id, \PDO::PARAM_INT);
                        $stmt->execute();

                        if ($stmt->rowCount() == 1) {
                            return $org_id;
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
    public function getAccountOrganizations($account_id) : array
    {
        $orgs = array();
        
        if ($this->connect(false)) {
            $stmt = $this->conn->prepare('SELECT t2.id, t2.name, t2.logo, t2.alias, t2.external ' .
                    'FROM organization_users as t1 JOIN organizations as t2 ON t1.organization_id = t2.id ' .
                    "WHERE t1.account_id = :id AND t1.organization_id != 1 AND t1.is_active = 1 AND t1.status = 1 AND t2.is_active = 1 ORDER By t2.name");            
            $stmt->bindParam(':id', $account_id, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount()) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $orgs[] = $row;
                }
            }
        }
        
        return $orgs;
    }
    
    public function signup()
    {
        $this->connect();
        
        $response = array();
        try {
            $payload = $this->payload();
            if ($this->isEmpty($payload->email ?? null) || $this->isEmpty($payload->username ?? null) ||
                    $this->isEmpty($payload->password ?? null) || $this->isEmpty($payload->flag ?? null)) {
                throw new \Exception('payload');
            }
            
            $model = new Accounts($this->conn);
            $stmt = $model->dataobject()->prepare('SELECT * FROM accounts WHERE email=:eml');
            $stmt->bindParam(':eml', $payload->email, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                throw new \Exception('email');
            }            
            $pstmt = $model->dataobject()->prepare('SELECT * FROM accounts WHERE username=:usr');
            $pstmt->bindParam(':usr', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('username')->getLength());
            $pstmt->execute();
            if ($pstmt->rowCount() == 1) {
                throw new \Exception('username');
            }
            
            $mailer = $this->getMailer();
            if ( ! $mailer) {
                throw new \Exception('mailer');
            }
            
            $content = new Contents($this->conn);
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
        $this->connect();
        
        $response = array();
        try {
            $payload = $this->payload();
            $flag = $payload->flag ?? 'en';            
            if ( ! isset($payload->email)) {
                throw new \Exception('invalid');
            }
            
            $model = new Accounts($this->conn);
            $stmt = $model->dataobject()->prepare('SELECT * FROM accounts WHERE email=:eml');
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
            
            $content = new Contents($this->conn);
            $content->setTables('templates');
            $templates = $content->getByKeyword('forgotten-password-reset');
            if ( ! isset($templates['full'][$flag]) ||
                    ! isset($templates['title'][$flag])) {
                throw new \Exception('template');
            }

            $useid = \uniqid('use');                        
            $forgot = new Forgot($this->conn);
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
            $login_link = $payload->login ?? (single::app()->webUrl(false) . '/dashboard/login');
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
        $this->connect();
        
        $payload = $this->payload();
        
        if (isset($payload->use)) {
            $model = new Forgot($this->conn);
            $record = $model->getBy('use_id', $payload->use);

            if (((int) $record['is_active']) == 1) {
                return $this->success($record);
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function setPassword()
    {
        $this->connect();
        
        $payload = $this->payload();
        if ( ! isset($payload->use_id) || empty($payload->use_id)
                || ! isset($payload->account) || empty($payload->account)
                || ! isset($payload->password) || empty($payload->password)
                || ! isset($payload->created_at) || empty($payload->created_at)) {
            return $this->error('Invalid request');
        }
        
        $forgot = new Forgot($this->conn);
        $recordf = $forgot->getBy('use_id', $payload->use_id);

        if ( ! $recordf
                || $payload->account != $recordf['account']
                || $payload->created_at != $recordf['created_at']) {
            return $this->error('Invalid payload');
        }
        
        $account = new Accounts($this->conn);
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
}
