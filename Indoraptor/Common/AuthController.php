<?php namespace Indoraptor;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\RBAC\RBACUser;

use Indoraptor\Account\AccountModel;
use Indoraptor\Account\OrganizationModel;
use Indoraptor\Account\OrganizationUserModel;
use Indoraptor\Localization\TranslationModel;

class AuthController extends IndoController
{
    public function entry()
    {
        try {
            $translation = new TranslationModel($this->conn);
            $translation->setTables('dashboard');
            $text = $translation->retrieve(single::language()->current());
            
            $payload = $this->payload();
            if ( ! isset($payload->username) || empty($payload->username) ||
                    ! isset($payload->password) || empty($payload->password)) {
                throw new \Exception($text['invalid-request'] ?? 'Request is not valid!');
            }
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE username=:usr OR email=:eml LIMIT 1");
            $stmt->bindParam(':eml', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('email')->getLength());
            $stmt->bindParam(':usr', $payload->username, \PDO::PARAM_STR, $model->getDescribe()->getColumn('username')->getLength());
            $stmt->execute();
            if ($stmt->rowCount() != 1) {
                throw new \Exception($text['error-incorrect-credentials'] ?? 'Invalid username or password');
            }

            $account = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ( ! (new Post())->asPassword($payload->password, $account['password'])) {
                throw new \Exception($text['error-incorrect-credentials'] ?? 'Invalid username or password');
            }
            if (((int) $account['status']) == 0) {
                throw new \Exception($text['error-account-inactive'] ?? 'User is not active');
            }
            
            unset($account['password']);
            
            $login_info = array('account_id' => $account['id']);
            
            $last = $this->getLastLoginOrg($account['id']);
            if ($last != null) {
                $login_info['organization_id'] = $last;
            }
            
            $account['jwt'] = $this->generate($login_info);
            
            return $this->success(array('account' => $account));
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    public function jwt()
    {
        try {
            $payload = $this->payload();
            if ( ! isset($payload->jwt)) {
                throw new \Exception('Please provide information!');
            }

            $validation = $this->validate($payload->jwt);
            if ( ! isset($validation['account_id'])) {
                throw new \Exception('Invalid JWT - Authentication failed!');
            }

            $model = new AccountModel($this->conn);
            $account = $model->getByID($validation['account_id']);
            if ( ! isset($account['id'])) {
                throw new \Exception('Account not found!');
            }

            unset($account['password']);

            $response = array('account' => $account);     
            
            $org_model = new OrganizationModel($this->conn);
            $org_user_model = new OrganizationUserModel($this->conn);            
            $stmt = $org_user_model->dataobject()->prepare('SELECT t2.id, t2.name, t2.logo, t2.alias, t2.external ' .
                    "FROM {$org_user_model->getTable()} as t1 JOIN {$org_model->getTable()} as t2 ON t1.organization_id = t2.id " .
                    "WHERE t1.account_id = :id AND t1.is_active = 1 AND t1.status = 1 AND t2.is_active = 1 ORDER By t2.name");            
            $stmt->bindParam(':id', $account['id'], \PDO::PARAM_INT);
            $stmt->execute();

            $index = 0;
            $organizations = array();
            $current = $validation['organization_id'] ?? 1;
            if ($stmt->rowCount()) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $organizations[$row['id'] == $current ? 0 : $index++] = $row;
                }
            }
            
            if (empty($response['organizations'])) {
                throw new \Exception('User doesn\'t belong to an organization!');
            } elseif ( ! isset($organizations[0])) {
               $organizations[0] = $organizations[1];
               unset($organizations[1]);
            }

            $response['organizations'] = $organizations;
            
            $rbac = new RBACUser();
            if ( ! $rbac->init($this->conn, $account['id'], $organizations[0]['alias'])) {
                throw new \Exception('RBAC user not set!');
            }
            
            $response['role_permissions'] = $rbac;

            return $this->success($response);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    final public function jwtOrganization()
    {
        try {
            $current_login = $this->accept();
            if ( ! $current_login) {
                throw new \Exception('Not allowed!');
            }

            $payload = $this->payload();
            if ( ! (isset($payload->account_id) && \is_int($payload->account_id))
                    ||  ! (isset($payload->organization_id) && \is_int($payload->organization_id))) {
                throw new \Exception('Invalid request!');            
            }
            
            $model = new AccountModel($this->conn);
            $account = $model->getByID($current_login['account_id']);
            if ( ! isset($account['id'])
                    || $account['id'] != $payload->account_id) {
                throw new \Exception('Invalid account!');
            }
            
            $org_model = new OrganizationModel($this->conn);
            $organization = $org_model->getByID($payload->organization_id);
            if ( ! isset($organization['id'])) {
                throw new \Exception('Invalid organization!');
            }
            
            $org_user_model = new OrganizationUserModel($this->conn);
            $user = $org_user_model->retrieve($organization['id'], $account['id']);
            if ( ! isset($user['id'])) {
                throw new \Exception('Account does not belong to an organization!');
            }

            $account_org_jwt = array(
                'account_id' => $account['id'],
                'organization_id' => $organization['id']);
            return $this->respond(array('jwt' => $this->generate($account_org_jwt)));
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    public function getLastLoginOrg($account_id)
    {
        $org_user_model = new OrganizationUserModel($this->conn);
            
        $last_org_query =
            'SELECT info ' .
            'FROM dashboard_log ' .
            "WHERE created_by = :id AND reason = 'organization' AND level = 2 " .
            'ORDER By id Desc ' .
            'LIMIT 1';

        $stmt = $org_user_model->dataobject()->prepare($last_org_query);
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
                            "FROM {$org_user_model->getTable()} " .
                            "WHERE organization_id = :org AND account_id = :account AND status = 1 AND is_active = 1 " .
                            'ORDER By id Desc ' .
                            'LIMIT 1';
                    $stmt = $org_user_model->dataobject()->prepare($org_user_query);
                    $stmt->bindParam(':org', $org_id, \PDO::PARAM_INT);
                    $stmt->bindParam(':account', $account_id, \PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() == 1) {
                        return $org_id;
                    }
                }
            }
        }
        
        return null;
    }
}
