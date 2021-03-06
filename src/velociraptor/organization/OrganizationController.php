<?php namespace Velociraptor\Organization;

use codesaur as single;
use codesaur\Base\File;
use codesaur\Globals\Post;
use codesaur\HTML\HTML5 as html;

use Velociraptor\Boot4\Card;
use Velociraptor\TwigTemplate;
use Velociraptor\DashboardController;
use Velociraptor\File\FileController;

use Indoraptor\Account\OrganizationDescribe;

class OrganizationController extends DashboardController
{
    public function index()
    {
        $template = $this->getTemplate(single::text('organization'));
        
        if ( ! single::user()->can('system_org_index')) {
            return $template->alertErrorPermission();
        }
        
        $query = '?logger=organization&controller=' . \urlencode($this->getMe());
        
        $card = new Card(array(single::text('organization'), 'text-danger text-uppercase'), 'la la-bank', 'border-danger mb-4');
        
        if (single::user()->can('system_org_insert')) {
            $card->addButton(single::text('new'), single::link('crud', array('action' => 'insert')) . $query,
                    'btn btn-danger shadow-sm text-uppercase', 'flaticon-add', 'data-target="#modal" data-toggle="modal"');
        }

        $card->addContent(new TwigTemplate(\dirname(__FILE__) . '/organization-index-table.html'));
        
        $template->addDeleteScript(array('logger' => 'organization', 'model' => 'Indoraptor\\Account\\OrganizationModel'));

        $template->render($card);
    }
    
    public function crud(string $action, $id)
    {
        try {

            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can("system_org_$action")) {
                return $this->getTemplate()->alertErrorPermission(null, 'flaticon-security', true,  true);
            }

            $query = '?logger=organization&controller=' . \urlencode($this->getMe());
            $crud = single::link('crud-submit', array('action' => $action)) . $query;
            
            $where = 'id != 1';
            if (isset($id)) {
                $response = $this->indopost('/record/retrieve?model='
                        . \urlencode('Indoraptor\\Account\\OrganizationModel'), array('id' => $id));
                
                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                } else {
                    $where .= " AND id != $id";
                }
            }
            
            $column  = (new OrganizationDescribe())->getTwigColumns($response['data']['record'] ?? array());
            
            $lookup = $this->getLookup(array('status'));
            $lookup['account'] = $this->getAccounts();
            
            if (single::user()->can('system_org_index')) {                
                $orgs_response = $this->indopost('/record/retrieve?model='
                        . \urlencode('Indoraptor\\Account\\OrganizationModel'),
                        array('condition' => array('WHERE' => $where, 'ORDER By' => 'name')));
                $lookup['organizations'] = $orgs_response['data']['rows'] ?? array();
            }
            
            $vars = array(
                'crud' => $crud, 'action' => $action,
                'column' => $column, 'lookup' => $lookup);
            
            (new TwigTemplate(\dirname(__FILE__) . '/organization-crud-action-modal.html', $vars))->render();
            
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
        if ( ! single::user()->can("system_org_$action")) {
            return false;
        }
            
        $record = (new OrganizationDescribe())->getPostValues();
        
        if ($action == 'update' && $record['id'] == 1
                && single::user()->account('id') != 1) {
            return false; // No one but root can edit codesaur organization!
        }
        
        if ($action == 'update'
                && ! isset($record['id'])) {
            return false;
        }
        
        if ($action == 'insert'
                && isset($record['id'])) {
            unset($record['id']);
        }
        
        $status = $this->indopost('/status', array('table' => 'organizations'));
        $auto_increment = (int) ($record['id'] ?? $status['data']['Auto_increment'] ?? 1);        
        if (\is_numeric($auto_increment)) {
            $file = (new FileController("/organizations/$auto_increment"));
            $file->allowExtensions((new File())->getAllowed(3));
            $logo = $file->upload('txt_logo');
            if (isset($logo['name'])) {
                $record['logo'] = $file->getPathUrl($logo['name']);
            }
        }
        
        if (isset($record['logo'])
                && $this->isEmpty($record['logo'])) {
            unset($record['logo']);
        }
        
        if (isset($record['alias'])) {
            $record['alias'] = \preg_replace('/[^A-Za-z0-9_-]/', '', $record['alias']);
        }
        
        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method('/record?model='
                . \urlencode('Indoraptor\\Account\\OrganizationModel'), array('record' => $record));
        
        return $response['data'] ?? null;
    }
    
    public function datatable()
    {
        $lookup = $this->getLookup(array('status'));
        
        $response = $this->indopost('/record/retrieve?model='
                . \urlencode('Indoraptor\\Account\\OrganizationModel'));
                
        if (single::user()->can('system_org_index')
                && isset($response['data']['clean'])) {
            $table = $response['data']['clean'];
            $query = '?logger=organization&controller=' . \urlencode($this->getMe());
            
            $rows = array();
            $rbac_link = single::link('crud', array('action' => 'index')) . '?logger=rbac&controller=' . \urlencode('Velociraptor\\RBAC\\RBACController');
            foreach ($response['data']['rows'] ?? array() as $record) {
                $row = [];

                $id = $record['id'];
                
//                if ( ! empty($record['external'])) {
//                    $id .= " - {$record['external']}";
//                }
                
                $row[] = $id;
                
                if (empty($record['logo'])) {
                    $row[] = '';
                } else {
                    $row[] = '<img src="' . $record['logo'] . '" style="height:54px">';
                }
                
                $rbac_query = '&alias=' . \urlencode($record['alias']) . '&title=' . \urlencode($record['name']);
                
                $row[] = \htmlentities($record['name']);
                
                if (single::user()->can('system_rbac')) {
                    $row[] = '<a href="' . $rbac_link . $rbac_query . '">' . \htmlentities($record['alias']) . '</a>';
                } else {
                    $row[] = \htmlentities($record['alias']);
                }
                
                $row[] = \htmlentities($lookup['status'][$record['status']] ?? $record['status']);

                $action = '';
                
                if (single::user()->can('system_org_update')) {
                    $action .= html::a(
                            array(
                                'class' => 'ajax-modal btn btn-primary shadow-sm',
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'href'  => single::link('crud', array('action' => 'update')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-edit"></i>'
                    );
                }
                if (single::user()->can('system_org_retrieve')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'class' => 'ajax-modal btn btn-info shadow-sm',
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'href'  => single::link('crud', array('action' => 'retrieve')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-eye"></i>'
                    );
                }
                if (single::user()->can('system_org_delete')) {  
                    $action .= html::nbsp() . html::a(
                            array(
                                'href'   => $record['id'],
                                'custom' => "alias=\"$table\"",
                                'class'  => 'delete btn btn-danger shadow-sm'
                            ), '<i class="la la-trash"></i>'
                    );
                }
                
                $row[] = $action ?? '';

                $rows[] = $row;
            }
        }
        
        $post = new Post();
        single::response()->json(array(
            'data' => $rows ?? array(),
            'recordsTotal' => \count($rows ?? array()),
            'recordsFiltered' => \count($rows ?? array()),
            'draw' => $post->has('draw') ? $post->value('draw', FILTER_VALIDATE_INT) : 0
        ));
    }
}
