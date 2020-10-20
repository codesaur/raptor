<?php namespace Indoraptor;

use codesaur\DataObject\Table;
use codesaur\MultiModel\MultiModel;

class RecordController extends IndoController
{
    public $model;
    public $payload;
    
    function __construct(bool $single = true, array $header = [], array $params = [], array $payload = [])
    {
        parent::__construct($single, $header, $params, $payload);
        
        $this->connect();
        
        $this->model = $this->grabmodel(true);        
        $this->payload = $this->payload(true);
    }
        
    public function insert()
    {
        if ( ! \in_array($this->model->getMe(), array(
            //'Indoraptor\\Localization\\LanguageModel',
            //'Indoraptor\\Localization\\TranslationModel'
        )) &&  ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        if ($this->payload['record']) {
            if ($this->model instanceof MultiModel) {
                $id = $this->model->inserts($this->payload['record']['primary'], $this->payload['record']['content']);
            } else {
                $id = $this->model->insert($this->payload['record']);
            }
        }

        if ($id ?? false) {
            $this->success(array(
                'id'    => $id,
                'model' => $this->model->getMe(),
                'table' => $this->model->getTable(),
                'clean' => $this->model->getTableClean()                
            ));
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function update()
    {
        if ( ! \in_array($this->model->getMe(), array(
            //'Indoraptor\\Localization\\LanguageModel',
            //'Indoraptor\\Localization\\TranslationModel'
        )) &&  ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        if ($this->payload['record']) {
            if ($this->model instanceof MultiModel) {
                $id = $this->model->updates($this->payload['record']['primary'], $this->payload['record']['content']);
            } elseif ($this->model instanceof Table) {
                $id = $this->model->update($this->payload['record']);
            }
        }

        if ($id ?? false) {
            $this->success(array(
                'id'    => $id,
                'model' => $this->model->getMe(),
                'table' => $this->model->getTable(),
                'clean' => $this->model->getTableClean()                
            ));
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function delete()
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        if (isset($this->payload['id']) &&
                $this->model->getDescribe()->hasColumn($this->model->primary)) {
            $idColumn = $this->model->getDescribe()->getColumn($this->model->primary);
            $idColumn->setValue($this->payload['id']);

            if ($this->callFunc(array(
                $this->model, $this->payload['callBack'] ?? 'delete'), array($idColumn))) {
                return $this->success(array(
                    'id'    => $this->payload['id'],
                    'model' => $this->model->getMe(),
                    'table' => $this->model->getTable(),
                    'clean' => $this->model->getTableClean()
                ));
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function retrieve()
    {
        if ( ! \in_array($this->model->getMe(), array(
            //'Indoraptor\\Localization\\LanguageModel',
            //'Indoraptor\\Localization\\TranslationModel'
        )) &&  ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $data = array();
        if (isset($this->payload['id'])) {
            if ($this->model instanceof MultiModel) {
                $data['record'] = $this->model->getByID($this->payload['id'], $this->payload['flag'] ?? null);
            } else {
                $data['record'] = $this->model->getByID($this->payload['id']);
            }
        } else {
            if ($this->model->getDescribe()->hasColumn('is_active')) {
                if (\strpos($this->payload['condition']['WHERE'] ?? '', 'is_active') === false) {
                    $is_active = $this->model instanceof MultiModel ? 'p.is_active=1' : 'is_active=1';
                    if (isset($this->payload['condition']['WHERE'])) {
                        $this->payload['condition']['WHERE'] .= " AND $is_active";
                    } else {
                        $this->payload['condition']['WHERE'] = $is_active;
                    }
                }
            }
            $data['rows'] = $this->model->getRows($this->payload['condition'] ?? array());
        }
        
        if ((isset($data['record']) && empty($data['record']))
                || (isset($data['rows']) && empty($data['rows']))) {
            return $this->error('Not Found');
        }

        $data['model'] = $this->model->getMe();
        $data['table'] = $this->model->getTable();
        $data['clean'] = $this->model->getTableClean();
        
        return $this->success($data);
    }
}
