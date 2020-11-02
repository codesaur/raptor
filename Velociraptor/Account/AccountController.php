<?php namespace Velociraptor\Account;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\HTML\Template;
use codesaur\Base\LogLevel;

use Velociraptor\TwigTemplate;
use Velociraptor\Boot4\Dashboard;
use Velociraptor\DashboardController;

use Indoraptor\Account\AccountDescribe;

class AccountController extends DashboardController
{    
    public function index()
    {
        $view = new Dashboard(single::text('accounts'));
        
        $table = single::request()->getParam('table');
        if ($table && $table != 'accounts') {
            $modal = \dirname(__FILE__) . "/$table-index-modal.html";
            if ( ! single::user()->can("system_{$table}_index")
                    || ! \file_exists($modal)) {
                return $view->noPermission(true);
            }
            
            $response = $this->indopost("/record/retrieve?table=$table&model="
                    . \urlencode('Indoraptor\\Account\\' . ($table == 'forgot' ? 'Forgot' : 'Account') . 'Model'),
                    array('condition' => array('WHERE' => 'is_active!=999')));
            
            $this->log($table, $this->getMe() . " нь $table хүснэгтийн мэдээллийг үзэж байна.", LogLevel::Record);            
            
            return (new TwigTemplate(
                    $modal,
                    array(
                        'login' => new LoginController(),
                        'rows' => $response['data']['rows'] ?? array())))->render();
        }
        
        $view->breadcrumb(array(single::text('accounts')));
        
        if ($this->orgIndex($view)) {
            return;
        }
        
        if ( ! single::user()->can('system_account_index')) {
            return $view->noPermission();
        }
        
        $response = $this->indopost('/record/retrieve?model='
                . \urlencode('Indoraptor\\Account\\AccountModel'),
                array('condition' => array('ORDER BY' => 'first_name')));
        $accounts = $response['data']['rows'] ?? array();
        
        $view->callout(single::text('accounts-note'), 'primary', 'flaticon2-avatar');
        $view->addDelete(array('logger' => 'account', 'model' => 'Indoraptor\\Account\\AccountModel'), 'table');
        
        $org_users_query = 'SELECT t1.account_id, t1.organization_id, t1.status ' .
                'FROM organization_users as t1 JOIN organizations as t2 ON t1.organization_id = t2.id ' .
                'WHERE t1.is_active = 1 AND t2.is_active = 1';
        $org_users_result = $this->indopost('/statement', array('sql' => $org_users_query));
        $org_users = $org_users_result['data'] ?? array();
        \array_walk($org_users, function($value) use (&$accounts) {
            if (isset($accounts[$value['account_id']])) {
                if ( ! isset($accounts[$value['account_id']]['organizations'])) {
                    $accounts[$value['account_id']]['organizations'] = array();
                }
                $accounts[$value['account_id']]['organizations'][$value['organization_id']] = $value['status'];
            }
        });
        
        $user_role_query = 'SELECT t1.role_id, t1.user_id, t2.name, t2.alias ' . 
                'FROM rbac_user_role as t1 JOIN rbac_roles as t2 ON t1.role_id = t2.id WHERE t1.is_active = 1';
        $user_role_result = $this->indopost('/statement', array('sql' => $user_role_query));
        $user_role = $user_role_result['data'] ?? array();
        \array_walk($user_role, function($value) use (&$accounts) {
            if (isset($accounts[$value['user_id']])) {
                if ( ! isset($accounts[$value['user_id']]['roles'])) {
                    $accounts[$value['user_id']]['roles'] = array();
                }
                $accounts[$value['user_id']]['roles'][] = "{$value['alias']}_{$value['name']}";
            }
        });
        
        $organizations_result = $this->indopost('/record/retrieve?model=' . \urlencode('Indoraptor\\Account\\OrganizationModel'));
        $vars = array(
            'accounts' => $accounts,
            'lookup' => $this->getLookup(array('status')),
            'organizations' => $organizations_result['data']['rows'] ?? array());

        $view->render(new TwigTemplate(\dirname(__FILE__) . '/account-index.html', $vars));
    }
    
    public function crud(string $action, $id)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            $alias = single::user()->organization('alias');
            $me = ($id ?? -777) == single::user()->account('id');
            
            if ($me && \in_array($action, array('update', 'retrieve'))) {
                $permission = 'self';
            } else {
                if (single::user()->can("system_account_$action")) {
                    $permission = 'system';
                } elseif (single::user()->can("{$alias}_account_$action")) {
                    $permission = 'organization';
                } else {
                    throw new \Exception("No permission for an action [$action]!");
                }
            }
            
            $title = single::text('accounts');
            $query = '?logger=account&controller=' . \urlencode($this->getMe());
            $index = single::link('crud', array('action' => 'index')) . $query;
            $crud = single::link('crud-submit', array('action' => $action)) . $query;
            
            if (isset($id)) {
                $response = $this->indopost('/record/retrieve?model='
                        . \urlencode('Indoraptor\\Account\\AccountModel'), array('id' => $id));
                
                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                }
                
                $login = $this->getLastLog($id, 'login', LogLevel::Security);
                $last_act = $this->getLastLog($id, 'request', LogLevel::Basic);
            }
            
            $column = (new AccountDescribe())->getTwigColumns($response['data']['record'] ?? array());
            
            $vars = array(
                'crud' => $crud,
                'column' => $column,
                'login' => $login ?? null,
                'last_act' => $last_act ?? null,
                'account' => $this->getAccounts(),
                'lookup' => $this->getLookup(array('status')));

            if ($permission == 'organization') {
                if ($action == 'insert') {
                    $this->orgCRUDInsert();
                } else {
                    return false;
                }
            } else {
                $view = new Dashboard();

                if ($action == 'retrieve') {
                    $caption = $response['data']['record']['first_name'] ?? single::text('account');
                } else {
                    if ($action == 'update') {
                        $caption = single::text('edit-account');
                    } else {
                        $caption = single::text('new-account');
                        $breadcrumb = single::text('add-new-account');
                    }

                    $delete = array('table' => 'accounts', 'logger' => 'account');
                    $view->addDelete($delete, '#tab-picture', single::text('delete-image-ask'), null, 'strip_file');
                }
                
                $view->title("$title - $caption");
                $view->breadcrumb(array($title, $index))->breadcrumb(array($breadcrumb ?? $caption));
                $view->render(new TwigTemplate(\dirname(__FILE__) . "/account-$action.html", $vars));
            }
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return (new Dashboard())->noPermission();
        }
    }
    
    public function submit(string $action)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            $record = (new AccountDescribe())->getPostValues();
            
            $alias = single::user()->organization('alias');
            $me = ($record['id'] ?? -777) == single::user()->account('id');
            
            if ($me && $action == 'update') {
                $permission = 'self';
            } else {
                if (single::user()->can("system_account_$action")) {
                    $permission = 'system';
                } elseif (single::user()->can("{$alias}_account_$action")) {
                    $permission = 'organization';
                } else {
                    throw new \Exception("No permission for an action [$action]!");
                }
            }
            
            if ($permission == 'organization') {
                if ($action == 'insert') {
                    return $this->orgSubmitInsert();
                } else {
                    return false;
                }
            }
            
            if ($action == 'update' && $record['id'] == 1
                    && single::user()->account('id') != 1) {
                throw new \Exception('No one but root can edit root account!');
            }
            
            if ($this->isEmpty($record['username'])) {
                throw new \Exception('[' . single::text('username') . '] ' . single::text('field-is-required'));
            }
            
            $post = new Post();
            $password_set = $post->hasArray(array('password_new', 'password_retype'))
                    && ! $this->isEmpty($post->value('password_new'))
                    && $post->value('password_new') == $post->value('password_retype');
            
            if ( ! $password_set) {
                if ($action == 'insert' ||
                        ! $this->isEmpty($record['password'])) {
                    throw new \Exception(single::text('new-password-error'));
                }
            }
            
            if ($action == 'update') {
                if ( ! isset($record['id'])) {
                    return false;
                }
                
                $method = 'indoput';
                $auto_increment = (int) $record['id'];                
                
                if ($password_set) {
                    $response = $this->indopost('/record/retrieve?model='
                            . \urlencode('Indoraptor\\Account\\AccountModel'), array('id' => $auto_increment));

                    if ( ! isset($response['data']['record']['password'])
                            || ! $post->asPassword($record['password'], $response['data']['record']['password'])) {
                        throw new \Exception(single::text('password-error'));
                    }
                    
                    $record['password'] = $post->asPassword($post->value('password_new'));
                } else {
                    unset($record['password']);
                }
            } else {
                if (isset($record['id'])) {
                    unset($record['id']);
                }
                
                $method = 'indopost';
                $status = $this->indopost('/status', array('table' => 'accounts'));
                $auto_increment = (int) $status['data']['Auto_increment'] ?? 1;

                $record['password'] = $post->asPassword($post->value('password_new'));
            }
            
            $response = $this->$method('/record?model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'), array('record' => $record));

            if ( ! isset($response['data'])) {
                throw new \Exception(single::text('account-exists') . '<br/>username/email');                
            }
            
            if (single::user()->can("system_account_$action")) {
                $response['data']['href'] = single::link('crud', array('action' => 'index'))
                        . '?logger=account&controller=' . \urlencode($this->getMe());
            } else {
                $response['data']['href'] = single::link('home');
            }

            return $response['data'];
        } catch (\Exception $e) {
            single::response()->json(array(
                'status'  => 'error',
                'message' => $e->getMessage(),
                'title'   => single::text('error')
            ));
            exit;
        }
    }
    
    public function organizationSet($id)
    {
        if (single::request()->getMethod() == 'GET') {
            if ( ! single::user()->can('system_account_organization_set')) {
                return (new Dashboard())->noPermission(true);
            }
            
            $sql =  'SELECT ou.organization_id ' .
                    'FROM organization_users as ou JOIN organizations as o ON ou.organization_id = o.id ' .
                    'WHERE ou.account_id = :id AND ou.is_active = 1 AND o.is_active = 1';
            $response = $this->indopost('/statement', array('sql' => $sql,
                'bind' => array(':id' => array('variable' => $id, 'type' => \PDO::PARAM_INT))));
            foreach ($response['data'] ?? array() as $value) {
                if (isset($active)) {
                    $active .= ',';
                } else {
                    $active = '';
                }
                $active .= $value['organization_id'];
            }

            $organizations_result = $this->indopost('/record/retrieve?model=' . \urlencode('Indoraptor\\Account\\OrganizationModel'));

            $vars = array(
                'id' => $id, 'current_organizations' => $active,
                'organizations' => $organizations_result['data']['rows'] ?? array());
            
            (new TwigTemplate(\dirname(__FILE__) . '/account-organization-set.html', $vars))->render();
            
            $this->log('organization-set', array(
                'account_id' => $id, 'organizations' => $active,
                'message' => "$id дугаартай хэрэглэгчийн байгууллагын мэдээллийг өөрчлөх үйлдлийг эхлүүллээ."
            ), LogLevel::Record, 'account', 10);
        } elseif (single::request()->getMethod() == 'POST') {
            if ( ! single::user()->can('system_account_organization_set')) {
                single::response()->json(array(
                    'status'  => 'error',
                    'title'   => single::text('error'),
                    'message' => single::text('system-no-permission')
                ));
                exit;
            }
            
            $post = new Post();
            if ($post->has('organizations')) {
                $organizations_post = $post->value('organizations');
                if ($organizations_post === false) {
                    $organizations_post = $post->value('organizations', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                }
            } else {
                $organizations_post = array();
            }

            if ( ! \is_array($organizations_post)) {
                $organizations_post = array($organizations_post);
            }

            if ($post->has('account_id')) {
                $account_id = $post->value('account_id');
            }
            
            if ($this->isEmpty($account_id ?? null)) {
                single::response()->json(array(
                    'status'  => 'error',
                    'title'   => single::text('error'),
                    'message' => single::text('invalid-values')
                ));
                exit;
            }

            $organizations = array();
            foreach ($organizations_post as $value) {
                $organizations[(int) $value] = true;
            }
            
            $logdata = array('account_id' => $id, 'organizations' => \implode(',', \array_keys($organizations)));

            $org_user_result = $this->indopost('/statement', array(
                'bind' => array(':id' => array('variable' => (int) $account_id, 'type' => \PDO::PARAM_INT)),
                'sql' => 'SELECT id,organization_id FROM organization_users WHERE account_id=:id AND is_active=1'));
            foreach ($org_user_result['data'] ?? array() as $row) {
                if (isset($organizations[(int) $row['organization_id']])) {
                    unset($organizations[(int) $row['organization_id']]);
                } else {
                    $response_org_delete = $this->indodelete('/record?model='
                            . \urlencode('Indoraptor\\Account\\OrganizationUserModel'), array('id' => $row['id']));

                    if (isset($response_org_delete['data']['id'])) {
                        $logdata = array('message' => "{$row['organization_id']} дугаартай байгууллагын хэрэглэгчийн бүртгэлээс $account_id дугаар бүхий хэрэглэгчийг хаслаа.");
                        $logdata['organization-id'] = $row['organization_id'];
                        $logdata['account-id'] = $account_id;
                        $this->log('organization-strip', $logdata, LogLevel::Record, 'account', 13);
                    }
                    
                }
            }

            foreach (\array_keys($organizations) as $value) {
                $response_org_set = $this->indopost('/record?model='
                        . \urlencode('Indoraptor\\Account\\OrganizationUserModel'),
                        array('record' => array('account_id' => $account_id, 'organization_id' => $value)));
                
                if (isset($response_org_set['data']['id'])) {
                    $logdata = array('message' => "$account_id дугаартай хэрэглэгчийг $value дугаар бүхий байгууллагад нэмэх үйлдлийг амжилттай гүйцэтгэлээ.");
                    $logdata['account-id'] = $account_id;
                    $logdata['organization-id'] = $value;
                    $this->log('organization-set', $logdata, LogLevel::Record, 'account', 14);
                }
            }
            
            single::response()->json(array(
                'status'  => 'success',
                'title'   => single::text('success'),
                'message' => single::text('record-update-success'),
                'href'    => single::link('crud', array('action' => 'index')) . '?logger=account&controller=' . \urlencode($this->getMe())
            ));
            
            $logdata['message'] = "$id дугаартай хэрэглэгчийн байгууллагын мэдээллийг өөрчлөх үйлдлийг амжилттай гүйцэтгэлээ.";
            
            $this->log('organization-update', $logdata, LogLevel::Record, 'account', 10);
        } else {
            single::app()->error('Wrong Method');
        }
    }
    
    public function approve()
    {
        try {
            if ( ! single::user()->can('system_account_insert')) {
                throw new \Exception("No permission for an action [approval]!");
            }
            
            $post = new Post();
            if ( ! $post->hasArray(array('id', 'status'))) {
                throw new \Exception(single::text('invalid-request'));
            }
            
            $id = $post->value('id', FILTER_VALIDATE_INT);
            $status = $post->value('status', FILTER_VALIDATE_INT);            
            $response = $this->indopost('/record/retrieve?table=newbie&model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'), array('id' => $id));
            
            if ( ! isset($response['data']['record'])) {
                throw new \Exception(single::text('invalid-values'));
            }
            
            $record = $response['data']['record'];            
            $username_or_email = "username='{$record['username']}' OR email='{$record['email']}'";                        
            $account = $this->indopost('/record/retrieve?model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'),
                    array('condition' => array('WHERE' => $username_or_email)));

            if (isset($account['data'])) {
                throw new \Exception(single::text('account-exists') . '<br/>username/email');
            }

            $record['status'] = $status;
            unset($record['id']);
            unset($record['is_active']);
            unset($record['created_at']);
            unset($record['created_by']);
            unset($record['updated_at']);
            unset($record['updated_by']);            
            $result = $this->indopost('/record?model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'), array('record' => $record));
            
            if ( ! isset($result['data'])) {
                throw new \Exception(single::text('account-insert-error'));
            }

            $this->log('approve-new-account', array(
                'message' => "Шинэ бүртгүүлсэн {$record['username']} нэртэй [{$record['email']}] хаягтай хэрэглэгчийн хүсэлтийг зөвшөөрч системд нэмлээ.", 'account' => $record
            ), LogLevel::Security, 'account', 9);

            $record['id'] = $id;
            $record['is_active'] = 0;
            unset($record['status']);
            $this->indoput('/record?table=newbie&model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'), array('record' => $record));
            
            $content = $this->indopost('/content',
                    array('table' => 'templates', '_keyword_' => array('approve-new-account')));

            if (isset($content['data']['approve-new-account']['title'][$record['code']]) &&
                    isset($content['data']['approve-new-account']['full'][$record['code']])) {
                $template = new Template();
                $template->source($content['data']['approve-new-account']['full'][$record['code']]);
                $template->set('email', $record['email']);
                $template->set('login', single::request()->getHttpHost() . single::link('login'));
                $template->set('username', $record['username']);
                
                $this->indopost('/email', array(
                    'to' => $record['email'],
                    'flag' => $record['code'],
                    'name' => $record['username'],
                    'message' => $template->output(),
                    'subject' => $content['data']['approve-new-account']['title'][$record['code']]
                ));
            }
            
            single::response()->json(array(
                'status'  => 'success',
                'alert'   => 'SweetAlert',
                'title'   => single::text('success'),
                'message' => single::text('account-insert-success'),
                'href'    => single::link('crud', array('action' => 'index')) .
                '?logger=account&controller=' . \urlencode($this->getMe())
            ));
        } catch (\Exception $e) {
            single::response()->json(array(
                'status'  => 'error',
                'title'   => single::text('error'),
                'message' => $e->getMessage()
            ));
        }
    }
    
    public function orgIndex(Dashboard $view)
    {
        $alias = single::user()->organization('alias');
        if ( ! single::user()->can($alias . '_account_index')
                || single::user()->is('system_coder')) {
            return false;
        }
        
        if (single::user()->can($alias . '_rbac_index')) {
            $rbac_link = single::link('crud', array('action' => 'index')) . 
                    '?logger=rbac&controller=' . \urlencode('Velociraptor\\RBAC\\RBACController') .
                    '&alias=' . \urlencode($alias) . '&title=' . \urlencode(single::user()->organization('name'));
            $view->addToolbar(array('title' => 'Хэрэглэгчийн дүрүүд'), 'flaticon-safe-shield-protection', 'btn-danger shadow-sm', $rbac_link, '#modal');
        }
        
        $org_account_query =
                'SELECT a.*, ou.status as org_status ' .
                'FROM accounts as a ' .
                ' INNER JOIN organization_users as ou ON a.id = ou.account_id ' .
                'WHERE ou.organization_id = :org AND a.id != 1 AND a.is_active = 1 AND ou.is_active = 1 ' .
                'ORDER By a.id';
        $org_account_result = $this->indopost('/statement',
                array('sql' => $org_account_query, 'bind' => array(':org' => array(
                    'variable' => (int) single::user()->organization('id'), 'type' => \PDO::PARAM_INT))));
        
        $accounts = array();
        foreach ($org_account_result['data'] ?? array() as $account) {
            unset($account['password']);
            $accounts[$account['id']] = $account;
        }
        
        $user_role_query =
                'SELECT rur.role_id, rur.user_id, rr.name, rr.alias ' . 
                'FROM rbac_user_role as rur ' .
                ' JOIN rbac_roles as rr ON rur.role_id = rr.id ' .
                "WHERE rur.is_active = 1 AND rr.is_active = 1 AND (rr.alias = :alias OR rr.alias = 'system')";
        $user_role_result = $this->indopost('/statement',
                array('sql' => $user_role_query, 'bind' =>
                    array(':alias' => array('variable' => single::user()->organization('alias')))));
        
        $user_role = $user_role_result['data'] ?? array();
        \array_walk($user_role, function($value) use (&$accounts) {
            if (isset($accounts[$value['user_id']])) {
                if ( ! isset($accounts[$value['user_id']]['roles'])) {
                    $accounts[$value['user_id']]['roles'] = array();
                }
                $accounts[$value['user_id']]['roles'][] = "{$value['alias']}_{$value['name']}";
            }
        });
        
        $vars = array(
            'accounts' => $accounts,
            'lookup' => $this->getLookup(array('status')));        
        
        $view->render(new TwigTemplate(\dirname(__FILE__) . '/org-account-index.html', $vars));
        
        return true;
    }
    
    public function orgCRUDInsert()
    {
        $accounts_query =
                'SELECT id, username, first_name, last_name ' .
                'FROM accounts ' .
                'WHERE is_active = 1 AND id != 1 ' .
                'ORDER By id';
        $accounts_result = $this->indopost('/statement', array('sql' => $accounts_query));                

        $org_accounts_query =
                'SELECT a.id ' .
                'FROM accounts as a ' .
                ' INNER JOIN organization_users as ou ON ou.account_id = a.id ' .
                'WHERE ou.organization_id = :org_id AND a.is_active = 1 AND ou.is_active = 1 ' .
                'GROUP By ou.account_id';
        $org_accounts_result = $this->indopost('/statement',
                array(
                    'sql' => $org_accounts_query,
                    'bind' => array(':org_id' => array('variable' => (int) single::user()->organization('id'), 'type' => \PDO::PARAM_INT))
                )
        );
        $org_accounts = $org_accounts_result['data'] ?? array();

        $accounts = array();
        foreach ($accounts_result['data'] ?? array() as $value) {
            $accounts[$value['id']] = array(
                'username' => $value['username'],
                'first_name' => $value['first_name'],
                'last_name' => $value['last_name']
            );
        }

        \array_walk($org_accounts, function($org_user) use (&$accounts) {
            if (isset($accounts[$org_user['id']])) {
                unset($accounts[$org_user['id']]);
            }
        });

        $roles_result = $this->indopost('/statement', array(
            'sql' => 'SELECT id, name, description FROM rbac_roles WHERE alias = :org_alias AND is_active = 1',
            'bind' => array(':org_alias' => array('variable' => single::user()->organization('alias')))));

        $query = '?logger=account&controller=' . \urlencode($this->getMe());
        $crud = single::link('crud-submit', array('action' => 'insert')) . $query;
        $values = array('crud' => $crud, 'accounts' => $accounts, 'roles' => $roles_result['data']);        
        (new TwigTemplate(\dirname(__FILE__) . '/org-account-insert-modal.html', $values))->render();
    }
    
    public function orgSubmitInsert()
    {
        $post = new Post();

        if ( ! $post->hasArray(array('account', 'organization', 'role', 'organization_alias'))) {
            return false;
        }
        
        $role = $post->value('role');
        $account = $post->value('account');
        $organization = $post->value('organization');
        $organization_alias = $post->value('organization_alias');
        if ($this->isEmpty($role) || $this->isEmpty($account)
                || $this->isEmpty($organization) || $this->isEmpty($organization_alias)) {
            return false;
        }

        if ($organization != single::user()->organization('id')) {
            return false;
        }
        
        $roles = \explode(',', $role);
        if ($this->isEmpty($roles)) {
            return false;
        }
        
        $org_roles_query = 'SELECT id FROM rbac_roles WHERE alias = :org_alias AND is_active = 1';
        $org_roles_result = $this->indopost('/statement', array(
            'sql' => $org_roles_query, 'bind' => array(':org_alias' => array('variable' => $organization_alias))));
        $org_roles = array();
        foreach ($org_roles_result['data'] ?? array() as $row) {
            $org_roles[$row['id']] = true;
        }

        foreach ($roles as $id => $role) {
            if ( ! isset($org_roles[$role])) {
                unset($roles[$id]);
            }
        }
        
        $response_org_user = $this->indopost('/record?model='
                . \urlencode('Indoraptor\\Account\\OrganizationUserModel'),
                array('record' => array('account_id' => $account, 'organization_id' => $organization)));

        if (isset($response_org_user['data']['id'])) {
            $logdata = array('message' => "$account дугаартай хэрэглэгчийг $organization дугаар бүхий байгууллагад нэмэх үйлдлийг амжилттай гүйцэтгэлээ.");
            $logdata['account-id'] = $account;
            $logdata['organization-id'] = $organization;
            $this->log('organization-set', $logdata, LogLevel::Record, 'account', 10);
        }
        
        foreach ($roles as $id) {
            $response_user_role = $this->indopost('/record?model='
                    . \urlencode('codesaur\\RBAC\\UserRole'),
                    array('record' => array('user_id' => $account, 'role_id' => $id)));
            
            if (isset($response_user_role['data']['id'])) {
                $logdata = array('message' => "$organization дугаартай байгууллагын $account дугаар бүхий хэрэглэгч дээр $id дугаартай дүр нэмэх үйлдлийг амжилттай гүйцэтгэлээ.");
                $logdata['role-id'] = $id;
                $logdata['account-id'] = $account;
                $logdata['organization-id'] = $organization;
                $this->log('role-set', $logdata, LogLevel::Record, 'account', 11);
            }
        }
        
        single::response()->json(array(
            'status'  => 'success',
            'title'   => single::text('success'),
            'message' => single::text('account-insert-success'),
            'href'    => single::link('crud', array('action' => 'index')) .
            '?logger=account&controller=' . \urlencode($this->getMe())
        ));
        exit;
    }
    
    public function kick()
    {
        try {
            $post = new Post();

            if ( ! $post->hasArray(array('account', 'organization', 'organization_alias'))) {
                throw new \Exception('Хүсэлт буруу!');
            }

            $account = $post->value('account');
            $organization = $post->value('organization');
            $organization_alias = $post->value('organization_alias');
            if ( $this->isEmpty($account)
                    || $this->isEmpty($organization)
                    || $this->isEmpty($organization_alias)) {
                throw new \Exception('Параметр дутуу өгөдсөн!');
            }

            if ($organization != single::user()->organization('id')) {
                throw new \Exception('Байгууллага буруу сонгосон байна!');
            }

            if ( ! single::user()->can($organization_alias . '_account_delete')) {
                throw new \Exception('Хэрэглэгч үйлдэл гүйцэтгэх эрхгүй байна!');
            }
            
            $org_user_result = $this->indopost('/statement', array(
                'bind' => array(
                    ':id' => array('variable' => (int) $account, 'type' => \PDO::PARAM_INT),
                    ':org_id' => array('variable' => (int) $organization, 'type' => \PDO::PARAM_INT)),
                'sql' => 'SELECT id FROM organization_users WHERE account_id=:id AND organization_id=:org_id AND is_active=1'));
            if ( ! isset($org_user_result['data'][0]['id'])) {
                throw new \Exception('Хэрэглэгч уг байгууллагад бүртгэгдээгүй байна!');
            }
            
            $response_org_delete = $this->indodelete('/record?model='
                    . \urlencode('Indoraptor\\Account\\OrganizationUserModel'), array('id' => $org_user_result['data'][0]['id']));
            
            if ( ! isset($response_org_delete['data']['id'])) {
                throw new \Exception('Хэрэглэгчийг байгууллагын бүртгэлээс хасаж чадсангүй!');
            }
            
            $logdata = array('message' => "$organization дугаартай байгууллагын хэрэглэгчийн бүртгэлээс $account дугаар бүхий хэрэглэгчийг хаслаа.");
            $logdata['organization-id'] = $organization;
            $logdata['account-id'] = $account;
            $this->log('organization-strip', $logdata, LogLevel::Record, 'account', 13);
            
            single::response()->json(array(
                'status'  => 'success',
                'title'   => single::text('success'),
                'message' => 'Хэрэглэгчийг байгууллагын бүртгэлээс хаслаа'
            ));
        } catch (\Exception $e) {            
            single::response()->json(array(
                'status'  => 'error',
                'title'   => single::text('error'),
                'message' => $e->getMessage()
            ));
        }
    }
}
