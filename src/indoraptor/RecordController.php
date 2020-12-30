<?php namespace Indoraptor;

use codesaur\DataObject\Table;
use codesaur\MultiModel\MultiModel;

class RecordController extends IndoController
{
    public function insert()
    {
        $model = $this->grabmodel(true);        
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $payload = $this->payload(true);
        if ($payload['record']) {
            if ($model instanceof MultiModel) {
                $id = $model->inserts($payload['record']['primary'], $payload['record']['content']);
            } else {
                $id = $model->insert($payload['record']);
            }
        }

        if ($id ?? false) {
            $this->success(array(
                'id'    => $id,
                'model' => $model->getMe(),
                'table' => $model->getTable(),
                'clean' => $model->getTableClean()                
            ));
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function update()
    {
        $model = $this->grabmodel(true);        
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $payload = $this->payload(true);
        if ($payload['record']) {
            if ($model instanceof MultiModel) {
                $id = $model->updates($payload['record']['primary'], $payload['record']['content']);
            } elseif ($model instanceof Table) {
                $id = $model->update($payload['record']);
            }
        }

        if ($id ?? false) {
            $this->success(array(
                'id'    => $id,
                'model' => $model->getMe(),
                'table' => $model->getTable(),
                'clean' => $model->getTableClean()                
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
        
        $model = $this->grabmodel(true);        
        $payload = $this->payload(true);
        if (isset($payload['id']) &&
                $model->getDescribe()->hasColumn($model->primary)) {
            $idColumn = $model->getDescribe()->getColumn($model->primary);
            $idColumn->setValue($payload['id']);

            if ($this->callFunc(array(
                $model, $payload['callBack'] ?? 'delete'), array($idColumn))) {
                return $this->success(array(
                    'id'    => $payload['id'],
                    'model' => $model->getMe(),
                    'table' => $model->getTable(),
                    'clean' => $model->getTableClean()
                ));
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function retrieve()
    {
        $model = $this->grabmodel(true);
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $data = array();

        $payload = $this->payload(true);
        if (isset($payload['id'])) {
            if ($model instanceof MultiModel) {
                $data['record'] = $model->getByID($payload['id'], $payload['flag'] ?? null);
            } else {
                $data['record'] = $model->getByID($payload['id']);
            }
        } else {
            if ($model->getDescribe()->hasColumn('is_active')) {
                if (\strpos($payload['condition']['WHERE'] ?? '', 'is_active') === false) {
                    $is_active = $model instanceof MultiModel ? 'p.is_active=1' : 'is_active=1';
                    if (isset($payload['condition']['WHERE'])) {
                        $payload['condition']['WHERE'] .= " AND $is_active";
                    } else {
                        $payload['condition']['WHERE'] = $is_active;
                    }
                }
            }
            $data['rows'] = $model->getRows($payload['condition'] ?? array());
        }
        
        if ((isset($data['record']) && empty($data['record']))
                || (isset($data['rows']) && empty($data['rows']))) {
            return $this->error('Not Found');
        }

        $data['model'] = $model->getMe();
        $data['table'] = $model->getTable();
        $data['clean'] = $model->getTableClean();
        
        return $this->success($data);
    }
}
