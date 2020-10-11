<?php namespace Indoraptor\Common;

use codesaur\Globals\Post;

use Indoraptor\Account\AccountModel;
use Indoraptor\Account\OrganizationModel;
use Indoraptor\Account\OrganizationUserModel;

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
            
            $model = new AccountModel($this->conn);
            $stmt = $model->dataobject()->prepare("SELECT * FROM {$model->getTable()} WHERE username=:usr OR email=:eml LIMIT 1");
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
            
            $login_info = array('account_id' => $account['id']);
            
            $last = $this->getLastLoginOrg($account['id']);
            if ($last != null) {
                $login_info['organization_id'] = $last;
            }
            
            $account['jwt'] = $this->generate($login_info);
            
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

            $model = new AccountModel($this->conn);
            $account = $model->getByID($validation['account_id']);
            if ( ! isset($account['id'])) {
                throw new \Exception('Account not found!');
            }

            unset($account['password']);

            $response = array('account' => $account);

            $org_model = new OrganizationModel($this->conn);
            $organization = $org_model->getByID($validation['organization_id'] ?? 1);
            if ( ! isset($organization['id'])) {
                throw new \Exception('Account does not belong to an organization!');
            }
            
            $response['organizations'] = $this->getAccountOrganizations($account['id']);

            if ( ! empty($response['organizations'])) {
                if (\count($response['organizations']) == 1) {
                    if ($response['organizations'][0]['id'] != $organization['id']) {
                        $organization = $org_model->getByID((int) $response['organizations'][0]['id']);
                    }
                }
            }

            $response['organization'] = $organization;

            $org_user_model = new OrganizationUserModel($this->conn);
            $user = $org_user_model->retrieve($organization['id'], $account['id']);
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
    
    final public function getJWT()
    {
        if ($this->single
                || ! $this->accept()) {
            $this->error('Not allowed!');
        }
        
        $payload = $this->payload(true);
        
        if (isset($payload['account_id']) && \is_int($payload['account_id']) &&
                isset($payload['organization_id']) && \is_int($payload['organization_id'])) {
            $account_org_jwt = array(
                'account_id' => $payload['account_id'],
                'organization_id' => $payload['organization_id']);
            return $this->respond(array('jwt' => $this->generate($account_org_jwt)));
        }
        
        $this->error('Invalid request!');
    }
    
    public function getLastLoginOrg($account_id)
    {
        if ($this->connect(false)) {
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
        }
        
        return null;
    }
    
    public function getAccountOrganizations($account_id) : array
    {
        $orgs = array();
        
        if ($this->connect(false)) {
            $org_model = new OrganizationModel($this->conn);
            $org_user_model = new OrganizationUserModel($this->conn);            
            $stmt = $org_user_model->dataobject()->prepare('SELECT t2.id, t2.name, t2.logo, t2.alias, t2.external ' .
                    "FROM {$org_user_model->getTable()} as t1 JOIN {$org_model->getTable()} as t2 ON t1.organization_id = t2.id " .
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
}
