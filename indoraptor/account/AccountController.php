<?php namespace Indoraptor\Account;

use codesaur\Base\LogLevel;
use codesaur\Globals\Server;

use Indoraptor\Logger\LoggerModel;
use Indoraptor\Localization\TranslationModel;

class AccountController extends \Indoraptor\IndoController
{
    public function signup()
    {
        try {
            $response = array();
            
            $payload = $this->payload();

            $translation = new TranslationModel($this->conn);
            $translation->setTables('dashboard');
            $text = $translation->retrieve($payload->flag ?? $this->getAppLanguageCode());

            if ($this->isEmpty($payload->email ?? null) || $this->isEmpty($payload->username ?? null) ||
                    $this->isEmpty($payload->password ?? null) || $this->isEmpty($payload->flag ?? null)) {
                throw new \Exception($text['invalid-request'] ?? 'Request is not valid!');
            }
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE email=:eml");
            $stmt->bindParam(':eml', $payload->email, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $log = array('error' => 'email', 'message' => "Бүртгэлтэй [$payload->email] хаягаар шинэ хэрэглэгч үүсгэх хүсэлт ирүүллээ. Татгалзав.");
                throw new \Exception($text['account-email-exists'] ?? 'It looks like email address belongs to an existing account.');
            }
            
            $pstmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE username=:usr");
            $pstmt->bindParam(':usr', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('username')->getLength());
            $pstmt->execute();
            if ($pstmt->rowCount() == 1) {
                $log = array('error' => 'username', 'message' => "Бүртгэлтэй [$payload->username] хэрэглэгчийн нэрээр шинэ хэрэглэгч үүсгэх хүсэлт ирүүллээ. Татгалзав.");
                throw new \Exception($text['account-exists'] ?? 'It looks like information belongs to an existing account.');
            }
            
            $model->setTable('newbie');
            $id = $model->insert(array(
                'code' => $payload->flag,
                'email' => $payload->email,
                'username' => $payload->username,
                'password' => $payload->password,
                'address' => $payload->organization_name));
            if ( ! $id) {
                $log = array('error' => 'newbie', 'message' => "Шинээр $payload->username нэртэй [$payload->email] хаягтай хэрэглэгч үүсгэх хүсэлт ирүүлсэн боловч, уг мэдээллээр урьд нь хүсэлт өгч байсныг бүртгэсэн байсан учир дахин хүсэлт бүртгэхээс татгалзав.");
                throw new \Exception($text['account-request-exists'] ?? 'It looks like information belongs to an existing request.');
            }
            
            $response['id'] = $id;
            $log = array('id' => $id, 'message' => "Шинээр $payload->username нэртэй [$payload->email] хаягтай хэрэглэгч үүсгэх хүсэлт ирүүлснийг хүлээн авч амжилттай бүртгэв.");
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            $response['message'] = $e->getMessage();
        } finally {            
            if ( ! empty($log)) {
                $log['address'] = (new Server())->determineIP();
                $log['payload'] = array(
                    'code' => $payload->flag ?? '',
                    'email' => $payload->email ?? '',
                    'username' => $payload->username ?? '');
                
                $logger = new LoggerModel($this->conn);
                $logger->setTable('account');
                $logger->insert(array(
                    'type' => 9,
                    'level'  => LogLevel::Security,
                    'info'   => \json_encode($log),
                    'reason' => 'request-new-account'
                ));
            }
            
            $this->success($response);
        }
    }
    
    public function forgot()
    {
        try {
            $response = array();

            $payload = $this->payload();
            $flag = $payload->flag ?? $this->getAppLanguageCode();            
     
            $translation = new TranslationModel($this->conn);
            $translation->setTables('dashboard');
            $text = $translation->retrieve($flag);

            if ( ! isset($payload->email)) {
                throw new \Exception($text['invalid-request'] ?? 'Request is not valid!');
            }
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE email=:eml");
            $stmt->bindParam(':eml', $payload->email, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() != 1) {
                $log = array('error' => 'account', 'message' => "Бүртгэлгүй [$payload->email] хаяг дээр нууц үг шинээр тааруулах хүсэлт илгээхийг оролдлоо. Татгалзав.");
                throw new \Exception($text['account-did-not-exists'] ?? 'No account with that email address exists.');
            }
            
            $record = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (((int) $record['is_active']) == 0) {
                $log = array('error' => 'inactive', 'message' => "Эрх нь нээгдээгүй хэрэглэгч [$payload->email] нууц үг шинэчлэх хүсэлт илгээх оролдлого хийв. Татгалзав.");
                throw new \Exception($text['error-account-inactive'] ?? 'User is not active');
            }

            $useid = \uniqid('use');                        
            $forgotModel = new ForgotModel($this->conn);
            $forgot = array(
                'flag'       => $flag,
                'use_id'     => $useid,
                'account'    => $record['id'],
                'email'      => $record['email'],
                'username'   => $record['username'],
                'last_name'  => $record['last_name'],
                'first_name' => $record['first_name']
            );

            if ( ! $forgotModel->insert($forgot)) {
                throw new \Exception($text['record-insert-error'] ?? 'Error occurred while inserting record.');
            }
            
            $response['forgot'] = $forgot;
            $log = array('forgot' => $response['forgot'], 'message' => "Хэрэглэгч {$response['forgot']['first_name']} {$response['forgot']['last_name']} [$payload->email] нь нууц үгийг шинээр тааруулах хүсэлт илгээснийг зөвшөөрлөө.");
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            $response['message'] = $e->getMessage();
        } finally {
            if ( ! empty($log)) {
                $log['address'] = (new Server())->determineIP();
                $log['payload'] = array('code' => $payload->flag ?? '', 'email' => $payload->email ?? '');
                
                $logger = new LoggerModel($this->conn);
                $logger->setTable('account');
                $logger->insert(array(
                    'type' => 9,
                    'level'  => LogLevel::Security,
                    'info'   => \json_encode($log),
                    'reason' => 'request-password'
                ));
            }
            
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
    
    public function getOrganizationsNames()
    {
        $model = new OrganizationModel($this->conn);
        $stmt = $model->dataobject()->prepare("SELECT name FROM {$model->getTable()} WHERE is_active=1 ORDER BY name");
        $stmt->execute();
        $names = array();
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $names[] = $data['name'];
        }
        
        $this->success($names);
    }
}
