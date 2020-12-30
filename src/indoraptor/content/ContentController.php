<?php namespace Indoraptor\Content;

class ContentController extends \Indoraptor\IndoController
{
    public function index()
    {
        $payload = $this->payload(true);
        
        if (($this->isInternal() || $this->accept())
                && isset($payload['table']) && isset($payload['_keyword_'])) {
            $model = new ContentModel($this->conn);
            $model->setTables($payload['table']);
            
            if (\is_array($payload['_keyword_'])) {
                $keywords = $payload['_keyword_'];
            } else {
                $keywords = array($payload['_keyword_']);
            }
            
            $data = array();
            foreach ($keywords as $word) {
                $data[$word] = $model->getByKeyword($word);
            }            
        }

        if (isset($data)) {
            return $this->success($data);
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function lookup()
    {
        if ($this->accept()) {
            $payload = $this->payload(true);
            if ( ! $this->isEmpty($payload['table'] ?? null)) {
                $model = new LookupModel($this->conn);
                $tables = \is_array($payload['table']) ? $payload['table'] : array($payload['table']);
                foreach ($tables as $table) {
                    $rows = $model->retrieve($table, $payload['flag'] ?? null);
                    $data[$model->getTableClean()] = $rows;
                }
            }
        }        
        
        if (isset($data)) {
            return $this->success($data);
        } else {
            return $this->error('Not Found');
        }
    }
}
