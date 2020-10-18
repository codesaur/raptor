<?php namespace Indoraptor\Localization;

class TranslationController extends \Indoraptor\IndoController
{
    public $text = array();
    
    function __construct(bool $single = true)
    {
        parent::__construct($single);
        
        $this->connect();
        
        $this->model = new TranslationModel($this->conn);
    }
    
    public function index()
    {
        if ($this->accept()) {
            return $this->success(array('names' => $this->model->getTranslationNames()));
        } else {
            return $this->error('Not Allowed!');
        }
    }

    public function retrieve()
    {
        $payload = $this->payload(true);
        if (isset($payload['table'])) {
            if (\is_array($payload['table'])) {
                $tables = \array_values($payload['table']);
            } else {
                $tables = array($payload['table']);
            }

            $flag = $payload['flag'] ?? null;
            
            $table_names = $this->model->getTranslationNames();
            
            $translations = array();
            foreach (\array_unique($tables) as $table) {
                $clean = \preg_replace('/[^A-Za-z0-9_-]/', '', $table);
                
                if ( ! \in_array($clean, $table_names)) {
                    $initial = new \Indoraptor\Initial();
                    if ( ! \method_exists($initial, 'translation_' . $clean . '_key')) {
                        continue;
                    }
                }
                
                $text = $this->getTranslations($table, $flag);
                
                if ( ! empty($text)) {
                    $translations[$table] = $text;
                }
            }
            
            if ( ! empty($translations)) {
                return $this->success($translations);
            }
        }
        
        return $this->error('Not Found');
    }
    
    public function getLocally($tables, $flag)
    {
        if ( ! \is_array($tables)) {
            $tables = array($tables);
        }
        
        foreach ($tables as $table) {
            $this->text += $this->getTranslations($table, $flag);
        }
    }
    
    public function text($key) : string
    {
        return $this->text[$key] ?? '{' . $key . '}';
    }
    
    public function getTranslations($table, $flag) : array
    {
        $this->model->setTables($table);
        
        return $this->model->retrieve($flag);
    }
    
    public function getBy()
    {
        $payload = $this->payload(true);
        if (isset($payload['_keyword_'])) {
            $found = $this->model->getByKeyword($payload['_keyword_']);
        }

        if (isset($found) && $found) {
            return $this->success($found);
        } else {
            return $this->error('Not Found');
        }
    }
}
