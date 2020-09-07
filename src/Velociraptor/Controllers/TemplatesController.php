<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\HTML\TwigTemplate;
use codesaur\HTML\HTML5 as html;

use Indoraptor\Describes\ContentDescribe;

use Velociraptor\Templates\Boot4\Card;
use Velociraptor\Templates\Boot4\Dashboard;

class TemplatesController extends RaptorController
{
    public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('document-templates'))
                ->breadcrumb(array(single::text('document-templates')));
        
        if ( ! single::user()->can('system_template_index')) {
            $view->noPermission();
        }
        
        $card = new Card(array(single::text('document-templates'), 'text-uppercase text-success'), 'flaticon-interface-4');

        $query = '?logger=reference&table=templates&controller=' . \urlencode($this->getMe());        
        if (single::user()->can('system_template_insert')) {
            $card->addButton(single::text('new'),
                    single::link('crud', array('action' => 'insert')) . $query,
                    'btn btn-success shadow-sm text-uppercase', 'flaticon-add');
        }
        
        if (single::user()->can('system_template_initial')) {
            $card->addButton(single::text('get-initial-code'),
                    single::link('crud', array('action' => 'initial')) .
                    "$query&model=" . \urlencode('Indoraptor\\Models\\Language') .
                    '&describe=' . \urlencode('Indoraptor\\Describes\\LanguageDescribe'),
                    'btn btn-clean btn-outline-success shadow-sm text-uppercase', 'la la-leaf', 'data-target="#modal" data-toggle="modal"');
        }

        $card->addContent(new TwigTemplate(velociraptor_html . '/template/index-table.html'));

        $view->addDelete(array('logger' => 'reference', 'model' => 'Indoraptor\\Models\\Contents'));
        
        $view->render($card);
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can("system_template_$action")) {
                (new Dashboard())->noPermission(false);
                throw new \Exception("No permission for an [$action]!");
            }
            
            $title = single::text('document-templates');
            $query = "?logger=reference&table=$table&controller=" . \urlencode($this->getMe());
            $crud = single::link('crud-submit', array('action' => $action)) . $query;
            $index = single::link('crud', array('action' => 'index')) . $query;
            
            if (isset($id)) {
                $response = $this->indopost("/record/retrieve?table=$table&model="
                        . \urlencode('Indoraptor\\Models\\Contents'), array('id' => $id));
                
                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                }
            }
            
            if ($action == 'insert') {
                $caption = single::text('add-record');
                $breadcrumb = single::text('add-record');
            } elseif ($action == 'update') {
                $caption = single::text('edit-record');
            } else {
                $caption = single::text('view-record');
            }
            
            $view = new Dashboard();
            $view->title("$title - $caption")->breadcrumb(array($title, $index))->breadcrumb(array($breadcrumb ?? $caption));

            $column  = (new ContentDescribe())->getTwigColumns($response['data']['record'] ?? array());
            
            $lookup = $this->getLookup(
                    array('status', 'template_type'));
            $lookup['account'] = $this->getAccounts();

            $vars = array(
                'lookup' => $lookup,
                'crud' => $crud, 'action' => $action, 'column' => $column);

            $view->renderTwig(velociraptor_html . '/template/crud-action.html', $vars);
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return false;
        }
    }
    
    public function submit(string $action, $table)
    {
        if ( ! isset($table)) {
            return false;
        }

        if ( ! single::user()->can("system_template_$action")) {
            return false;
        }

        $describe = new ContentDescribe();
        $record = $describe->getPrimary()->getPostValues();
        $content = $describe->getContent()->getPostValues(single::language()->codes());
        
        if ($action == 'update'
                && ! isset($record['id'])) {
            return false;
        }
        
        if ($action == 'insert'
                && isset($record['id'])) {
            unset($record['id']);
        }
        
        $data = array('primary' => $record, 'content' => $content);

        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method('/record?model='
                . \urlencode('Indoraptor\\Models\\Contents')
                . "&table=$table", array('record' => $data));
        
        if (isset($response['data'])) {
            $response['data']['href'] = single::link('crud',
                    array('action' => 'index')) . "?logger=reference&table=$table&controller=" . \urlencode($this->getMe());
        }

        return $response['data'] ?? null;
    }
    
    public function datatable()
    {
        $lookup = $this->getLookup(array('status', 'template_type'));
        
        $response = $this->indopost('/record/retrieve?table=templates&model='
                .  \urlencode('Indoraptor\\Models\\Contents'), array('condition' => array('ORDER BY' => '_keyword_')));
        if (isset($response['data']['clean']) && single::user()->can('system_template_index')) {
            $table = $response['data']['clean'];
            $query = "?logger=reference&table=$table&controller=" . \urlencode($this->getMe());
            $rows = array();
            foreach ($response['data']['rows'] ?? array() as $record) {
                $row = [];

                $row[] = $record['_keyword_'];

                foreach (single::language()->codes() as $code) {
                    $row[] =  \htmlentities($record['title'][$code]);
                }

                $row[] = \htmlentities($lookup['template_type'][$record['type']] ?? $record['type']);
                $row[] = \htmlentities($lookup['status'][$record['status']] ?? $record['status']);
                
                $action = '<div class="float-right">';
                if (single::user()->can('system_template_retrieve')) {
                    $action .= html::a(
                            array(
                                'class' => 'btn btn-info shadow-sm',
                                'href'  => single::link('crud', array('action' => 'retrieve')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-eye"></i>'
                    );
                }
                if (single::user()->can('system_template_update')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'class' => 'btn btn-primary shadow-sm',
                                'href'  => single::link('crud', array('action' => 'update')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-edit"></i>'
                    );
                }
                if (single::user()->can('system_template_delete')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'href'   => $record['id'],
                                'custom' => "alias=\"$table\"",
                                'class'  => 'delete btn btn-danger shadow-sm'), 
                                '<i class="la la-trash"></i>'
                    );
                }
                $action .= '</div>';
                
                $row[] = $action;

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
