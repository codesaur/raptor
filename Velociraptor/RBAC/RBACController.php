<?php namespace Velociraptor\RBAC;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\Base\LogLevel;

use codesaur\RBAC\RolesDescribe;
use codesaur\RBAC\PermissionsDescribe;
use codesaur\RBAC\RolePermissionDescribe;

use Velociraptor\Boot4\Card;
use Velociraptor\TwigTemplate;
use Velociraptor\Boot4\Dashboard;
use Velociraptor\DashboardController;

class RBACController extends DashboardController
{
   public function index()
    {
        if ($this->orgIndex()) {
            return;
        }
        
        $alias = single::request()->getParam('alias');
        $title = single::request()->getParam('title');
        $organizations = single::link('crud', array('action' => 'index')) .
                '?logger=organization&controller=' . \urlencode('Velociraptor\\Organization\\OrganizationController');
            
        $view = new Dashboard();
        $view->title('RBAC')->breadcrumb(array(single::text('organization'), $organizations))->breadcrumb(array('RBAC'));
        
        if ( ! single::user()->can('system_rbac')) {
            return $view->noPermission();
        }
        
        $card = new Card(
                array("RBAC / $alias" . ($title ? " / $title" : ''), 'text-danger'),
                'flaticon-safe-shield-protection', 'border-danger shadow');
        
        $query = "?logger=rbac&alias=$alias&controller=" . \urlencode($this->getMe());
        $card->addButton('Add Role', single::link('crud', array('action' => 'insert')) . "$query&thing=role",
                'btn btn-info shadow-sm', 'flaticon-add', 'data-target="#modal" data-toggle="modal"');
        $card->addButton('Add Permission', single::link('crud', array('action' => 'insert')) . "$query&thing=permission",
                'btn btn-danger shadow-sm', 'flaticon-add', 'data-target="#modal" data-toggle="modal"');
        
        $payload = array('bind' => array(':alias' => array('variable' => $alias)));        
        
        $payload['sql'] = 'SELECT id,name,description FROM rbac_roles WHERE alias=:alias AND is_active=1';
        $roles = $this->indopost('/statement', $payload);
        
        $payload['sql'] = 'SELECT id,name,description FROM rbac_permissions WHERE alias=:alias AND is_active=1 ORDER By module';
        $permissions = $this->indopost('/statement', $payload);
        
        $payload['sql'] = 'SELECT role_id,permission_id FROM rbac_role_perm WHERE alias=:alias AND is_active=1';
        $rp = $this->indopost('/statement', $payload);
        
        $role_permission = array();
        foreach ($rp['data'] ?? array() as $row) {
            $role_permission[$row['role_id']][$row['permission_id']] = true;
        }

        $card->addContent(new TwigTemplate(
                \dirname(__FILE__) . '/rbac-index.html',
                array(
                    'roles' => $roles['data'] ?? array(),
                    'permissions' => $permissions['data'] ?? array(),
                    'alias' => $alias, 'role_permission' => $role_permission)));

        $view->render($card);
    }
    
    public function crud(string $action)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            $thing = single::request()->getParam('thing');
            $alias = single::request()->getParam('alias');
            $org_alias = single::user()->organization('alias');
            $response['data']['record'] = array('alias' => $alias);
            
            if ( ! single::user()->can('system_rbac')) {                
                if ($thing == 'user_role' &&
                        ! single::user()->is('system_coder') &&
                        single::user()->can("{$org_alias}_rbac_user_role")) {
                    $thing = "{$org_alias}_rbac_user_role";
                } else {
                    return (new Dashboard())->noPermission(true);
                }
            }
            
            $vars = array('action' => $action, 'alias' => $alias);
            
            switch ($thing) {
                case 'role': {
                    $vars['column'] = (new RolesDescribe())->getTwigColumns($response['data']['record'] ?? array());
                } break;
                case 'permission': {
                    $vars['column'] = (new PermissionsDescribe())->getTwigColumns($response['data']['record'] ?? array());
                    $modules = $this->indopost('/statement',
                            array(
                                'sql' => 'SELECT DISTINCT module FROM rbac_permissions WHERE alias=:alias AND is_active=1',
                                'bind' => array(':alias' => array('variable' => $alias))));
                    $vars['modules'] = $modules['data'] ?? array();
                } break;
                case "{$org_alias}_rbac_user_role":
                case 'user_role': {
                    $account_id = single::request()->getParam('id');
                    if ( ! $account_id) {
                        throw new \Exception('Please provide an account id!');
                    }
                    
                    $vars['account_id'] = $account_id;

                    if ($thing == "{$org_alias}_rbac_user_role") {
                        $rbacs = array();

                        $organizations_query = 'SELECT alias,name FROM organizations WHERE alias = :org_alias AND is_active = 1 ORDER By id desc';
                        $roles_query = 'SELECT id, alias, name, description FROM rbac_roles WHERE alias = :org_alias AND is_active = 1';
                        $current_role_query = 'SELECT rur.role_id FROM rbac_user_role as rur INNER JOIN rbac_roles as rr ON rur.role_id = rr.id WHERE rur.user_id = :user_id AND rr.alias = :org_alias AND rur.is_active = 1 AND rr.is_active = 1';
                    } else {
                        $rbacs = array('common' => 'Common');
                        
                        $organizations_query = "SELECT alias, name FROM organizations WHERE alias !='common' AND is_active = 1 ORDER By id desc";
                        $roles_query = 'SELECT id, alias, name, description FROM rbac_roles WHERE is_active = 1';
                        $current_role_query = "SELECT rur.role_id FROM rbac_user_role as rur INNER JOIN rbac_roles as rr ON rur.role_id = rr.id WHERE rur.user_id = :user_id AND '' != :org_alias AND rur.is_active = 1 AND rr.is_active = 1";
                    }
                    
                    $organizations = $this->indopost('/statement', array(
                        'sql' => $organizations_query,
                        'bind' => array(':org_alias' => array('variable' => $org_alias))));
                    foreach ($organizations['data'] ?? array() as $row) {
                        if (isset($rbacs[$row['alias']])) {
                            $rbacs[$row['alias']] .= ", {$row['name']}";
                        } else {
                            $rbacs[$row['alias']] = $row['name'];
                        }
                    }
                    $vars['rbacs'] = $rbacs;
                    
                    $roles = \array_map(function() { return array(); }, \array_flip(\array_keys($rbacs)));
                    
                    $sql_result = $this->indopost('/statement', array(
                        'sql' => $roles_query,
                        'bind' => array(':org_alias' => array('variable' => $org_alias))));
                    $roles_result = $sql_result['data'] ?? array();
                    \array_walk($roles_result, function($value) use (&$roles) {
                        if ( ! isset($roles[$value['alias']])) {
                            $roles[$value['alias']] = array();
                        }
                        $roles[$value['alias']][$value['id']] = array($value['name']);
                        
                        if ( ! empty($value['description'])) {
                            $roles[$value['alias']][$value['id']][] = $value['description'];
                        }
                    });
                    $vars['roles'] = $roles;
                    
                    $user_role_result = $this->indopost('/statement', array(
                        'sql' => $current_role_query,
                        'bind' => array(
                            ':user_id' => array('variable' => $account_id),
                            ':org_alias' => array('variable' => $org_alias))));
                    foreach ($user_role_result['data'] ?? array() as $row) {
                        if (isset($vars['current_role'])) {
                            $vars['current_role'] .= ',';
                        } else {
                            $vars['current_role'] = '';
                        }
                        $vars['current_role'] .= $row['role_id'];
                    }
                    
                    $thing = single::request()->getParam('thing');
                } break;
            }

            $vars['crud'] = single::link('crud-submit', array('action' => $action))
                    . "?logger=rbac&thing=$thing&alias=" . \urlencode($alias)
                    . '&controller=' . \urlencode($this->getMe());
            
            (new TwigTemplate(\dirname(__FILE__) . "/rbac-crud-$action-$thing-modal.html", $vars))->render();
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return false;
        }
    }
    
    public function submit(string $action)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            $thing = single::request()->getParam('thing');
            $org_alias = single::user()->organization('alias');
            
            if (($thing == 'user_role'
                    && single::user()->can("{$org_alias}_rbac_user_role"))
                || single::user()->can('system_rbac')) {
                $submit = "submit_$thing";
            } else {
                return false;
            }
            
            if ($this->hasMethod($submit) && $this->isCallable($submit)) {
                $result = $this->$submit($action);
            }
            
            if (isset($result['id'])) {
                if ($submit == 'submit_role_permission') {
                    $result['alert'] = 'notifyMe';
                } elseif ($submit == 'submit_user_role') {
                    $result['href'] = single::link('crud', array('action' => 'index'))
                            . '?logger=account&controller=Velociraptor\\Account\\AccountController';
                } else {
                    $result['href'] = single::link('crud', array('action' => 'index'))
                            . '?logger=rbac&controller=' . \urlencode($this->getMe())
                            . '&alias=' . \urlencode(single::request()->getParam('alias'));
                }
            }
            
            return $result ?? false;
        } catch (\Exception $e) {
            single::response()->json(array(
                'status'  => 'error',
                'message' => $e->getMessage(),
                'title'   => single::text('error')
            ));
            exit;
        }
    }
    
    function submit_role(string $action)
    {
        $record = (new RolesDescribe())->getPostValues();
        
        if ($action == 'update'
                && ! isset($record['id'])) {
            return false;
        }
        
        if ($action == 'insert'
                && isset($record['id'])) {
            unset($record['id']);
        }
        
        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method('/record?model='
                . \urlencode('codesaur\\RBAC\\Roles'), array('record' => $record));
        
        return $response['data'] ?? null;
    }
    
    function submit_permission(string $action)
    {
        $record = (new PermissionsDescribe())->getPostValues();
        
        if ($action == 'update'
                && ! isset($record['id'])) {
            return false;
        }
        
        if ($action == 'insert'
                && isset($record['id'])) {
            unset($record['id']);
        }
        
        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method('/record?model='
                . \urlencode('codesaur\\RBAC\\Permissions'), array('record' => $record));
        
        return $response['data'] ?? null;
    }
    
    function submit_role_permission(string $action)
    {
        $record = (new RolePermissionDescribe())->getPostValues();
        
        if ( ! isset($record['alias']) ||
                ! isset($record['role_id']) ||
                ! isset($record['permission_id'])) {
            return false;
        }

        $role_permission = $this->indopost(
                '/statement?model=' . \urlencode('codesaur\\RBAC\\RolePermission'),
                array(
                    'sql' => 'WHERE alias=:alias AND role_id=:role AND permission_id=:permission AND is_active=1',
                    'bind' => array(
                        ':alias' => array('variable' => $record['alias']),
                        ':role' => array('variable' => $record['role_id'], 'type' => \PDO::PARAM_INT),
                        ':permission' => array('variable' => $record['permission_id'], 'type' => \PDO::PARAM_INT)
                    )
                )
        );
        
        if ($action == 'insert') {
            if ( ! isset($role_permission['data'][0])) {
                $response = $this->indopost('/record?model='
                        . \urlencode('codesaur\\RBAC\\RolePermission'), array('record' => $record));
            }
        } 
        
        if ($action == 'update') {
            if (isset($role_permission['data'][0])) {
                $response = $this->indodelete('/record?model='
                        . \urlencode('codesaur\\RBAC\\RolePermission'), array('id' => $role_permission['data'][0]['id']));
            }            
        }
        
        return $response['data'] ?? null;
    }
    
    function submit_user_role(string $action)
    {
        $post = new Post();
        if ($post->has('roles')) {
            $post_roles = $post->value('roles');
            if ($post_roles === false) {
                $post_roles = $post->value('roles', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            }
        } else {
            $post_roles = array();
        }
        
        if ( ! \is_array($post_roles)) {
            $post_roles = array($post_roles);
        }
        
        if ($post->has('account_id')) {
            $account_id = $post->value('account_id');
        }
        
        if ($this->isEmpty($account_id ?? null)) {
            return false;
        }
        
        $roles = array();
        foreach ($post_roles as $value) {
            $roles[(int)$value] = true;
        }
        
        $org_alias = single::user()->organization('alias');
        if ( ! single::user()->is('system_coder') 
                && single::user()->can("{$org_alias}_rbac_user_role")) {
            $user_role_result = $this->indopost('/statement', array(
                'sql' => 'SELECT rur.id, rur.role_id FROM rbac_user_role as rur INNER JOIN rbac_roles as rr ON rur.role_id = rr.id WHERE rur.user_id = :user_id AND rr.alias = :org_alias AND rur.is_active = 1 AND rr.is_active = 1',
                'bind' => array(':user_id' => array('variable' => (int) $account_id, 'type' => \PDO::PARAM_INT), ':org_alias' => array('variable' => $org_alias))));
            
            $org_roles_query = 'SELECT id FROM rbac_roles WHERE alias = :org_alias AND is_active = 1';
            $org_roles_result = $this->indopost('/statement', array(
                'sql' => $org_roles_query, 'bind' => array(':org_alias' => array('variable' => $org_alias))));
            $org_roles = array();
            foreach ($org_roles_result['data'] ?? array() as $row) {
                $org_roles[$row['id']] = true;
            }
            
            foreach ($roles as $id => $role) {
                if ( ! isset($org_roles[$id])) {
                    unset($roles[$id]);
                }
            }
        } else {
            $user_role_result = $this->indopost('/statement', array(
                'sql' => 'SELECT id, role_id FROM rbac_user_role WHERE user_id = :user_id AND is_active = 1',
                'bind' => array(':user_id' => array('variable' => (int) $account_id, 'type' => \PDO::PARAM_INT))));
        }

        foreach ($user_role_result['data'] ?? array() as $row) {
            if (isset($roles[(int) $row['role_id']])) {
                unset($roles[(int) $row['role_id']]);
            } else {
                $response_role_delete = $this->indodelete('/record?model='
                        . \urlencode('codesaur\\RBAC\\UserRole'), array('id' => $row['id']));

                if (isset($response_role_delete['data']['id'])) {
                    $logdata = array('message' => "$account_id дугаартай хэрэглэгчээс {$row['id']} дугаар бүхий дүрийг хаслаа.");
                    $logdata['role-id'] = $value;
                    $logdata['account-id'] = $account_id;
                    $this->log('role-strip', $logdata, LogLevel::Record, 'account', 12);
                }
            }
        }
        
        foreach (\array_keys($roles) as $value) {
            $response_user_role = $this->indopost('/record?model='
                    . \urlencode('codesaur\\RBAC\\UserRole'),
                    array('record' => array('user_id' => $account_id, 'role_id' => $value)));
            
            if (isset($response_user_role['data']['id'])) {
                $logdata = array('message' => "$account_id дугаартай хэрэглэгч дээр $value дугаар бүхий дүр нэмэх үйлдлийг амжилттай гүйцэтгэлээ.");
                $logdata['role-id'] = $value;
                $logdata['account-id'] = $account_id;
                $this->log('role-set', $logdata, LogLevel::Record, 'account', 11);
            }
        }
        
        return array(
            'id' => $account_id,
            'model' => 'Accounts',
            'clean' => 'rbac_user_role');
    }
    
    public function orgIndex()
    {
        $alias = single::user()->organization('alias');
        if ( ! single::user()->can($alias . '_account_index')
                || single::user()->is('system_coder')) {
            return false;
        }
        
        $payload = array('bind' => array(':alias' => array('variable' => $alias)));        
        
        $payload['sql'] = 'SELECT id,name,description FROM rbac_roles WHERE alias=:alias AND is_active=1';
        $roles = $this->indopost('/statement', $payload);
        
        $payload['sql'] = 'SELECT id,name,description FROM rbac_permissions WHERE alias=:alias AND is_active=1 ORDER By module';
        $permissions = $this->indopost('/statement', $payload);
        
        $payload['sql'] = 'SELECT role_id,permission_id FROM rbac_role_perm WHERE alias=:alias AND is_active=1';
        $rp = $this->indopost('/statement', $payload);
        
        $role_permission = array();
        foreach ($rp['data'] ?? array() as $row) {
            $role_permission[$row['role_id']][$row['permission_id']] = true;
        }

        $vars = array(
            'roles' => $roles['data'] ?? array(),
            'role_permission' => $role_permission,
            'permissions' => $permissions['data'] ?? array());
        
        (new TwigTemplate(\dirname(__FILE__) . '/rbac-org-index.html', $vars))->render();
        
        return true;
    }
}
