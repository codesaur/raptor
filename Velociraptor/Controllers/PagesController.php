<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\HTML\TwigTemplate;
use codesaur\HTML\HTML5 as html;

use Indoraptor\Describes\PageDescribe;

use Velociraptor\Templates\Boot4\Card;
use Velociraptor\Templates\Boot4\Dashboard;

class PagesController extends RaptorController
{
    public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('pages'))
                ->breadcrumb(array(single::text('pages')));

        if ( ! single::user()->can(single::user()->organization('alias') . '_pages_index')) {
            $view->noPermission();
        }

        $view->alert(single::text('pages-note'), 'flaticon2-list-2', 'alert-primary alert-elevate alert-dismissible');        
        
        $card = new Card(array(single::text('pages'), 'text-primary text-uppercase'), 'flaticon2-list-3', 'border-primary');        
        
        $query = '?logger=pages' . '&controller=' . \urlencode($this->getMe()) . '&table=' . single::user()->organization('alias');
        if (single::user()->can(single::user()->organization('alias') . '_pages_insert')) {
            $card->addButton(single::text('new'),
                    single::link('crud', array('action' => 'insert')) . $query,
                    'btn btn-success shadow-sm text-uppercase', 'flaticon-add');
        }        
        if (single::user()->can(single::user()->organization('alias') . '_pages_initial')) {
            $card->addButton(single::text('get-initial-code'),
                    single::link('crud', array('action' => 'initial')) .
                    "{$query}&model=" . \urlencode('Indoraptor\\Models\\Pages') .
                    '&describe=' . \urlencode('Indoraptor\\Describes\\PageDescribe'),
                    'btn btn-clean btn-outline-success shadow-sm text-uppercase', 'la la-leaf', 'data-target="#modal" data-toggle="modal"');
        }

        $card->addContent(new TwigTemplate(velociraptor_html . '/pages/index-table.html'));

        $view->addDelete(array('logger' => 'pages', 'model' => 'Indoraptor\\Models\\Pages'));
        
        $view->render($card);
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can(single::user()->organization('alias') . "_pages_$action")) {
                (new Dashboard())->noPermission(false);
                throw new \Exception("No permission for an [$action]!");
            }

            $title = single::text('pages');
            $query = "?logger=pages&table=$table&controller=" . \urlencode($this->getMe());
            $crud = single::link('crud-submit', array('action' => $action)) . $query;
            $index = single::link('crud', array('action' => 'index')) . $query;
            
            if (isset($id)) {
                $response = $this->indopost("/record/retrieve?table=$table&model="
                        . \urlencode('Indoraptor\\Models\\Pages'), array('id' => $id));
                
                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                }
            }
            
            if ($action == 'insert') {
                $caption = single::text('new-page');
            } elseif ($action == 'update') {
                $caption = single::text('edit-page');
            } else {
                $caption = single::text('view-record');
            }
            
            $query_pages = 'SELECT id,parent_id,title ' .
                    "FROM {$table}_pages as p INNER JOIN {$table}_pages_content as c ON p.id = c.t_id " .
                    "WHERE c.code = :code AND p.type = 'menu' AND p.is_active = 1 ORDER By parent_id, position, id";
            $result_pages = $this->indopost('/statement', array('sql' => $query_pages,
                'bind' => array(':code' => array('variable' => single::flag()))));
            
            $parents = array();
            $rows_parents = $result_pages['data'] ?? array();
            foreach ($rows_parents as $page) {
                $ancestors = array();
                $path = $this->getParentsPath($page, $rows_parents, $ancestors);
                
                if (\in_array($id ?? -1, $ancestors)) {
                    continue;
                }
                
                $parents[$page['id']] = array(
                    'title' => $path,
                    'id' => $page['id'],
                    'parent_id' => $page['parent_id']
                );
            }
            
            $view = new Dashboard();
            $view->title("$title - $caption")->breadcrumb(array($title, $index))->breadcrumb(array($caption));
            
            $column = (new PageDescribe($table))->getTwigColumns($response['data']['record'] ?? array());
            
            $lookup = $this->getLookup(array('page_types', 'menu_css_types', 'blog_style'));
            $lookup['yesno'] = array(0 => single::text('no'), 1 => single::text('yes'));
            $lookup['account'] = $this->getAccounts();


            $vars = array(
                'crud' => $crud, 'action' => $action,
                'velociraptor_html' => velociraptor_html,
                'column' => $column, 'lookup' => $lookup, 'parents' => $parents);

            $view->renderTwig(velociraptor_html . '/pages/crud-action-pages.html', $vars);
            
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

        if ( ! single::user()->can(single::user()->organization('alias') . "_pages_$action")) {
            return false;
        }

        $describe = new PageDescribe($table);
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
        
        if (isset($record['alias'])
                && $this->isEmpty($record['alias'])) {
            unset($record['alias']);
        }
        
        $data = array('primary' => $record, 'content' => $content);
        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method('/record?model='
                . \urlencode('Indoraptor\\Models\\Pages')
                . "&table=$table", array('record' => $data));
        
        if (isset($response['data'])) {
            $response['data']['href'] = single::link('crud',
                    array('action' => 'index')) . "?logger=pages&table=$table&controller=" . \urlencode($this->getMe());
        }

        return $response['data'] ?? null;
    }
    
    public function datatable()
    {
        $rows = array();
        $table = single::user()->organization('alias');
        $response = $this->indopost(
                "/record/retrieve?table=$table&model=" .  \urlencode('Indoraptor\\Models\\Pages'),
                array('condition' => array('ORDER BY' => 'parent_id, position, id')));
        if (isset($response['data']['clean']) &&
                single::user()->can(single::user()->organization('alias') . '_pages_index')) {
            $table = $response['data']['clean'];            
            $records = $response['data']['rows'] ?? array();
            $lookup = $this->getLookup(array('page_types', 'menu_css_types'));
            $query = "?logger=pages&table=$table&controller=" . \urlencode($this->getMe());
            foreach ($records as $record) {
                $row = array();
                
                if ($record['type'] == 'menu') {
                    if ($record['menu_type'] != 'default') {
                        $type = $lookup['menu_css_types'][$record['menu_type']] ?? $record['menu_type'];
                    } elseif ($record['parent_id'] == 0) {
                        $type = single::text('main-menu');
                    } else {
                        $type = '';
                    }
                } else {
                    $type = $lookup['page_types'][$record['type']] ?? $record['type'];
                }
                
                $row[] = $record['id'] ;
                
                $row[] = html::img(['src' => single::app()->resourceUrl() . '/boot4/no-image-' . single::flag() . '.gif']);
                
                $titles = $status = '';
                
                foreach (single::language()->codes() as $code) {
                    $flag = html::img(['src' => single::app()->resourceUrl() . "/flags/16x11/$code.png", 'style' => 'position:relative;top:-2px']) . ' ';
                    
                    if ($titles != '') {
                        $titles .= '<br />';
                    }
                    
                    $titles .= "$flag<span>" . $this->getTitlePath($record['id'], $records, $code, 'primary') . '</span>';
                    
                    if ($record['status'][$code]) {
                        $status .= $flag;
                    }
                }
                
                if ( ! empty($type)) {
                    $titles .= '<span class="badge badge-pill badge-secondary text-lowercase float-right">' . \htmlentities($type) . '</span>';
                }
                
                $row[] = $titles;
                
                $row[] = $record['position'];
                
                $row[] = $status;
                
                $action = '<div class="float-right">';
                if (single::user()->can('system_pages_retrieve')) {
                    $action .= html::a(
                            array(
                                'class' => 'btn btn-info shadow-sm',
                                'href'  => single::link('crud', array('action' => 'retrieve')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-eye"></i>'
                    );
                }
                if (single::user()->can('system_pages_update')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'class' => 'btn btn-primary shadow-sm',
                                'href'  => single::link('crud', array('action' => 'update')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-edit"></i>'
                    );
                }
                if (single::user()->can('system_pages_delete')) {
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
    
    public function getTitlePath(int $id, array $records, string $code, $color = 'secondary')
    {
        $escaped = \htmlentities($records[$id]['title'][$code]);
        $title = "<span class=\"text-$color\">$escaped</span>";
        
        if ($records[$id]['parent_id'] != 0) {
            $title = $this->getTitlePath($records[$id]['parent_id'], $records, $code) . ' » ' . $title;
        }
        
        return $title;
    }
    
    public function getParentsPath($row, array $rows, array &$parents)
    {
        $parents[] = $row['id'];
        
        $title = $rows[$row['id']]['title'];
        
        if ($rows[$row['id']]['parent_id'] != 0) {
            if (! isset($rows[$rows[$row['id']]['parent_id']])) {
                return $title;
            }
            
            $title = $this->getParentsPath($rows[$rows[$row['id']]['parent_id']], $rows, $parents) . ' » ' . $title;
        }
        
        return $title;
    }
}
