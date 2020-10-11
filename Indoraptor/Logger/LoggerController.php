<?php namespace Indoraptor\Logger;

use Indoraptor\Common\IndoController;

class LoggerController extends IndoController
{
    function __construct(bool $single = true)
    {
        parent::__construct($single);
        
        $this->connect();
    }
    
    public function index($table, $id = null)
    {
        if ($this->accept()) {
            $model = new LoggerModel($this->conn);
            $model->setTable($table);

            if (isset($id)) {
                $data = $model->getByID($id);
                if ( ! $this->getParam('dont-decode-info')) {
                    $data['info'] = \json_decode($data['info'], true);
                }
            } else {
                $limit = $this->getParam('limit');
                $condition = array('ORDER BY' => 'id Desc');

                if ($limit) {
                    $condition['LIMIT'] = $limit;
                }
                
                $data = \array_values($model->getRows($condition));

                if ( ! $this->getParam('dont-decode-info')) {
                    foreach ($data as &$row) {
                        $row['info'] = \json_decode($row['info'], true);
                    }
                }
            }

            if (isset($data)) {
                \array_walk_recursive($data, function (&$v, $k) {
                    if (\in_array($k, array(
                        'jwt', 'token', 'pin', 'password'))) {
                        $v = '*** hidden info ***'; 
                    }
                });
                
                return $this->success($data);
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function names()
    {
        $names = array();
        if ($this->accept()) {
            $model = new LoggerModel($this->conn);
            $names = $model->getNames();
        }
        
        if (empty($names)) {
            return $this->error('Not Found');            
        } else {
            return $this->success(array('names' => $names));
        }
    }
    
    public function insert($table)
    {
        $record = array();
        $payload = $this->payload(true);
        if (isset($payload['info'])) {
            $record['info'] = $payload['info'];
        }
        if (isset($payload['type'])) {
            $record['type'] = $payload['type'];
        }
        if (isset($payload['reason'])) {
            $record['reason'] = $payload['reason'];
        }
        if (isset($payload['level'])) {
            $record['level'] = $payload['level'];
        }
        if (isset($payload['created_by'])) {
            $record['created_by'] = $payload['created_by'];
        }
        
        if ( ! empty($record)) {
            $model = new LoggerModel($this->conn);
            $model->setTable($table);
            $id = $model->insert($record);
        }
        
        if (isset($id) && $id) {
            return $this->success($model->getByID($id));
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function select($table)
    {
        $data = array();
        if ($this->accept()) {
            $model = new LoggerModel($this->conn);
            $model->setTable($table);
            
            $payload = $this->payload(true);
            $pdostmt = $model->selectBy($payload, $payload['condition'] ?? array());
            
            $data['rows']  = $model->getStatementRows($pdostmt);
            $data['model'] = $model->getMe();
            $data['table'] = $model->getTable();
            $data['clean'] = $model->getName();            
        }
        
        if (empty($data['rows'] ?? array())) {
            return $this->error('Not Found');            
        } else {
            return $this->success($data);
        }
    }
}
