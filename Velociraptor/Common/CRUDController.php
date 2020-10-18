<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Base\Base;
use codesaur\Globals\Post;
use codesaur\Globals\Server;
use codesaur\Base\LogLevel;
use codesaur\HTML\TwigTemplate;
use codesaur\DataObject\Describe;
use codesaur\MultiModel\MultiDescribe;

// CRUD are the four basic functions of persistent storage
// [C]reate/insert or add new entries
// [R]ead, retrieve, search, list or view existing entries
// [U]pdate or edit/modify existing entries
// [D]elete/deactivate/remove existing entries

class CRUDController extends DashboardController
{
    public $id    = null;
    public $table = null;
    public $model = null;
    
    function __construct()
    {
        parent::__construct();
        
        $post = new Post();
        
        if ( ! single::request()->hasParam('logger')) {
            if ($post->has('logger')) {
                $logger = $post->value('logger');
            }

            if (isset($logger)) {
                single::request()->addParam('logger', $logger);
            }
        }

        if (single::request()->hasParam('id')) {
            $this->id = single::request()->getParam('id');
        } else {
             if ($post->has('id')) {
                $this->id = $post->value('id');
            }
        }
        
        if (single::request()->hasParam('table')) {
            $this->table = single::request()->getParam('table');
        } else {
             if ($post->has('table')) {
                $this->table = $post->value('table');
            }
        }
        
        if (single::request()->hasParam('model')) {
            $this->model = \urldecode(single::request()->getParam('model'));
        } else {
             if ($post->has('model')) {
                $this->model = $post->value('model');
            }
        }

        $this->controller = $this->grab('controller');
    }
    
    public function act(string $action)
    {
        $message = '';
        $logdata = array();
        if ($this->model) {
            $logdata['model'] = $this->model;
            $message .= "$this->model мэдээллийн ";
        }
        if ($this->table) {
            $logdata['table'] = $this->table;
            $message .= "$this->table хүснэгтэд ";
        }
        if ($this->id) {
            $logdata['record'] = $this->id;
            $message .= "$this->id дугаартай ";
        }
        
        $post = new Post();
        if (single::request()->hasParam('callBack')) {
            $callBack = \urldecode(single::request()->getParam('callBack'));
        } elseif ($post->has('callBack')) {
            $callBack = $post->value('callBack');
        } else {
            $callBack = null;
        }
        
        if ($callBack) {
            $logdata['callBack'] = $callBack;
        }
                
        $logdata['crud']['action'] = $action;
        
        switch ($action) {
            case 'index': $this->execute('index'); break;
            case 'initial': $this->initial($this->getInitialCode($this->model, $this->table)); break;
            case 'delete': {
                $result = $this->delete($callBack);
                
                if ($result) {
                    $logdata['message'] = "{$message}бичлэгийг устгаx үйлдлийг амжилттай гүйцэтгэлээ.";

                    if ( ! $this->table) {
                        $logdata['message'] .= " Хүснэгт: $result";
                    }
                    
                    $level = LogLevel::Record;
                } else {
                    $logdata['message'] = "{$message}бичлэгийг устгаx үйлдлийг амжилтгүй гүйцэтгэлээ!";
                    
                    if ($result === null) {
                        $logdata['message'] .= ' Параметр буруу өгөгдсөн.';
                    }
                    
                    $level = LogLevel::Error;
                }
                
                $logdata['post'] = $post->direct();
                
                $this->log($action, $logdata, $level);
            } break;
            default: {
                if ($action == 'insert') { $message .= 'шинэ бичлэг нэмэх ';
                } elseif ($action == 'update') { $message .= 'бичлэгт засварлах ';
                } elseif ($action == 'retrieve') { $message .= 'бичлэгийг харах ';
                } else { $message .= "бичлэгт харгалзах $action "; }
                
                $crud = \in_array($action, array('insert', 'update', 'retrieve'));
                
                if ($crud) {
                    $result = $this->execute('crud', array($action, $this->id, $this->table, $this->model));
                } else {
                    $result = $this->execute($action, $post->direct());
                }
                
                if ($result) {
                    if ($this->controller instanceof Base) {
                        $logdata['message'] = $this->controller->getMe();
                    } else {
                        $logdata['message'] = $this->getMe();
                    }
                    
                    if ($crud) {
                        $logdata['message'] .= " нь $message үйлдлийг эхлүүллээ.";
                        $level = $action == 'retrieve' ? LogLevel::View : LogLevel::Record;
                    } else {
                        $logdata['message'] .= " нь $message үйлдлийг амжилттай гүйцэтгэлээ.";
                        
                        if (\is_array($result)) {
                            $logdata += $result;
                        } else {
                            $logdata['message'] .= " $result";
                        }
                        
                        $level = LogLevel::Basic;
                    }
                } else {
                    if ($crud) {
                        $logdata['message'] = "$message үйлдлийг эхлүүлэх үед алдаа гарлаа.";
                    } else {
                        $logdata['message'] = "$message үйлдлийг амжилтгүй гүйцэтгэлээ!";
                        
                        if ($result === null) {
                            $logdata['message'] .= ' Параметр буруу өгөгдсөн.';
                        }
                    }
                    $level = LogLevel::Error;
                }
                
                $this->log($action, $logdata, $level);
            } break;
        }
        
        $this->execute('after' . \ucfirst($action), $post->direct(), false);
        
        exit;
    }
    
    public function execute(string $action, array $params = [], bool $haltOnError = true)
    {
        if ( ! $this->controller instanceof Base) {
            if ($this->hasMethod($action) &&
                    $this->isCallable($action)) {
                return $this->callFuncArray(array($this, $action), $params);
            } elseif ($haltOnError) {
                single::app()->error('Invalid or undefined object!');
            }
        } elseif ($this->controller->hasMethod($action) &&
                $this->controller->isCallable($action)) {
            return $this->callFuncArray(array($this->controller, $action), $params);
        } elseif ($haltOnError) {
            single::app()->error("Action named $action is not part of {$this->controller->getMe()}!");
        }
        
        return null;
    }
    
    public function initial($data)
    {
        if ($this->controller instanceof Base) { 
            $controller = $this->controller->getMe();
        }

        $template = new TwigTemplate(\dirname(__FILE__) . '/initial-code-modal.html');
        $template->set('title', $controller ?? '');
        $template->set('generator', single::about());
        $template->set('time', \date('Y-m-d H:i:s'));
        $template->set('address', (new Server())->determineIP());
        $template->set('model', $data['model'] ?? '');
        $template->set('table', $data['table'] ?? '');
        $template->set('clean', $data['clean'] ?? '');
        $template->set('code', $data['code'] ?? array());
        $template->render();
        
        $this->log('code',
                $template->get('title') . ' нь ' . $template->get('model') . ' мэдээллийн ' .
                $template->get('clean') . ' хүснэгтээс үүсгэгч кодыг нь авч байна.', LogLevel::Record);
    }

    final public function submit(string $action)
    {
        if (\in_array($action, array('insert', 'update'))) {
            $submit_action = 'submit_' . \ucfirst($action);
            if ($this->controller->hasMethod($submit_action)
                    && $this->controller->isCallable($submit_action)) {
                $result = $this->controller->$submit_action($this->table);
            } elseif ($this->controller->hasMethod('submit')
                    && $this->controller->isCallable('submit')) {
                $result = $this->controller->submit($action, $this->table, $this->model);
            }
        }
        
        if ( ! isset($result) || $result === false) {
            single::response()->json(array(
                'status'  => 'error',
                'title'   => single::text('error'),
                'message' => single::text('invalid-request')
            ));
            exit;
        }
        
        $id = $result['id'] ?? null;
        $model = $result['model'] ?? '';
        $clean = $result['clean'] ?? '';
        
        $success = isset($id) && $id !== false;
        
        $text = $this->controller->getMe() . " нь $model мэдээллийн $clean хүснэгтэд " .
                ($action == 'insert' ? 'шинэ бичлэг нэмэх ' : 'бичлэг засварлах ') . 'үйлдлийг ' .
                ($success ? "амжилттай гүйцэтгэлээ. Бичлэгийн дугаар: $id" : 'амжилтгүй гүйцэтгэлээ!');
        
        $this->log(
                $action, array('message' => $text, 'record' => $id),
                $success ? LogLevel::Record : LogLevel::Error);


        if (isset($id)) {
            $title = $success ? single::text('success') : single::text('error');
            $type = $success ? ($action == 'insert' ? 'success' : 'info') : 'error';
            $message = $success ? single::text("record-$action-success") : single::text("record-$action-error");
        } else {
            $type = 'default';
            $title = single::text('error');
            $message = single::text('record-error-unknown');
        }

        $response = array(
            'status'  => $type,
            'title'   => $title,
            'message' => $message
        );

        if (isset($result['href'])) {
            $response['href'] = $result['href'];
        }

        if (isset($result['alert'])) {
            $response['alert'] = $result['alert'];
        } elseif ($success) {
            $response['alert'] = 'SweetAlert';
        }

        if (single::buffer()->getLength() == 0) {
            single::response()->json($response);
        }
        
        exit;
    }
    
    public function delete($callBack)
    {
        // TODO: permission shalgadag bolgoh!!!
        if ($this->id && $this->model) {
            $payload = array('id' => $this->id);
            
            $uri = '/record?model=' . \urlencode($this->model);
            if ($this->table) {
                $uri .= "&table=$this->table";
            }
            
            if ($callBack) {
                $payload['callBack'] = $callBack;
            }
            
            $response = $this->indodelete($uri, $payload);
            
            if ($response['data']['id'] ?? false) {
                single::response()->json(array(
                    'status'  => 'success',
                    'title'   => single::text('success'),
                    'message' => single::text('record-successfully-deleted')
                ));
                
                return $response['data']['table'] ?? 'unknown';
            }
        }
        
        single::response()->json(array(
            'title'   => single::text('error'),
            'status'  => isset($response) ? 'default' : 'error',
            'message' => isset($response) ? single::text('cant-complete-request') : single::text('invalid-values')
        ));
        
        return isset($response) ? false : null;
    }
    
    public function strip_file()
    {
        $post = new Post();
        $files_id = $post->has('files_id') ? $post->value('files_id') : null;
        if ($this->table && $files_id) {
            $payload = array('id' => $files_id);
            
            $query = "table=$this->table&model=" . \urlencode('Indoraptor\\File\\FilesModel');            
            $result = $this->indopost("/record/retrieve?$query", $payload);            
            if (isset($result['data']['record'])) {                
                $response = $this->indodelete("/record?$query", $payload);
                if (isset($response['data']['id'])) {
                    $actual = $this->indopost('/record/retrieve?model='
                            . \urlencode('Indoraptor\\File\\FileModel'),
                            array('id' => $result['data']['record']['file']));
                    if (isset($actual['data']) && $actual['data']['record']['protection'] == 1) {
                        $done = $this->indodelete('/record?model='
                                . \urlencode('Indoraptor\\File\\FileModel'),
                                array('id' => $result['data']['record']['file']));
                        if ( ! isset($done['data'])) {
                            unset($actual);
                        }
                    }

                    $post = new Post();
                    if ($post->has('postBack')) {
                        $postBack = $post->value('postBack');
                        if (\is_callable($postBack)) {
                            $postBack($result['data']['record'], $actual['data']['record'] ?? array());
                        }
                    }
                    
                    single::response()->json(array(
                        'status'  => 'success',
                        'title'   => single::text('success'),
                        'message' => single::text('delete-file-success')
                    ));
                    
                    return array(
                        'files' => $result['data']['record'],
                        'file'  => $actual['data']['record'] ?? null
                    );
                }
            }
        }
        
        single::response()->json(array(
            'status'  => 'error',
            'title'   => single::text('error'),
            'message' => single::text('delete-file-error')
        ));
        
        return false;
    }    
    
    function getInitialCode(
            $model, $table, $exclude = array(
                'id', 'is_active', 'read_count', 'created_by', 'updated_by')) : array
    {
        $query = 'model=' . \urlencode($model);
        if ($table) {
            $query .= "&table=$table";
        }
        
        $response = $this->indopost("/record/retrieve?$query");
        $data = $response['data'] ?? array();
        
        $describe = $this->grab('describe', single::user()->organization('alias'));
        if ($describe instanceof MultiDescribe) {
            $primary = $describe->getPrimary();
            $content = $describe->getContent();
        } elseif ($describe instanceof Describe) {
            $primary = $describe;
        }

        if ( ! isset($primary) || empty($data)) {
            return array();
        }
        
        $data['code'] = array();
        foreach ($data['rows'] ?? array() as $row) {
            $method = 'insert';
            $record = 'array(';
            foreach ($primary->getColumns() as $column) {
                if ( ! \in_array($column->getName(), $exclude)) {
                    if (isset($row[$column->getName()])) {
                        if ($record !== 'array(') {
                            $record .= ', ';
                        }
                        $record .= "'" . $column->getName() . "' => ";
                        if ($column->getPostType() == 1) {
                            $record .= \filter_var($row[$column->getName()], FILTER_VALIDATE_INT);
                        } else {
                            $record .= "'" . \htmlspecialchars(\strtr($row[$column->getName()], array("'" => "\'"))) . "'";
                        }
                    }
                }
            }
            $record .= ')';
            if (isset($content)) {
                $method = 'replaces';
                $each = array();
                foreach ($content->getColumns() as $column) {
                    if (isset($row[$column->getName()])) {
                        if (\is_array($row[$column->getName()])) {
                            foreach ($row[$column->getName()] as $key => $value) {
                                if (isset($each[$key])) {
                                    $each[$key] .= ', ';
                                } else {
                                    $each[$key] = 'array(';
                                }
                                $each[$key] .= "'" . $column->getName() . "' => ";
                                if ($column->getPostType() == 1) {
                                    $each[$key] .= \filter_var($value, FILTER_VALIDATE_INT);
                                } else {
                                    $each[$key] .= "'" . \htmlspecialchars(\strtr($value, array("'" => "\'"))) . "'";
                                }
                            }
                        }
                    }
                }
                if ( ! empty($each)) {
                    $multidata = 'array(';
                    foreach ($each as $key => $value) {
                        if ($multidata !== 'array(') {
                            $multidata .= ', ';
                        }
                        $multidata .= "'$key' => $value)";
                    }
                    $multidata .= ')';
                }
            }
            $code = '$m->' . $method . '(' . $record;
            if (isset($multidata)) {
                $code .= ", $multidata";
            }
            $code .= ');';
            $data['code'][] = $code;
        }
        
        return $data;
    }
}
