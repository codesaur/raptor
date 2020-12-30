<?php namespace Indoraptor\Localization;

use codesaur as single;

use Indoraptor\Initial;

class TranslationController extends \Indoraptor\IndoController
{
    public function index()
    {
        if ($this->accept()) {
            return $this->success(array('names' =>
                (new TranslationModel($this->conn))->getTranslationNames()));
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
            
            $model = new TranslationModel($this->conn);
            $table_names = $model->getTranslationNames();
            
            $initialModel = single::app()->getNamespace() . 'Initial';
            $initial = \class_exists($initialModel) ? new $initialModel() : new Initial();

            $translations = array();
            foreach (\array_unique($tables) as $table) {
                $clean = \preg_replace('/[^A-Za-z0-9_-]/', '', $table);
                
                if ( ! \in_array($clean, $table_names)) {
                    if ( ! \method_exists($initial, 'translation_' . $clean . '_key')) {
                        continue;
                    }
                }
                
                $model->setTables($table);
                $text = $model->retrieve($flag);
                
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
    
    public function getBy()
    {
        $payload = $this->payload(true);
        if (isset($payload['_keyword_'])) {
            $found = (new TranslationModel($this->conn))->getByKeyword($payload['_keyword_']);
        }

        if (isset($found) && $found) {
            return $this->success($found);
        } else {
            return $this->error('Not Found');
        }
    }
}
