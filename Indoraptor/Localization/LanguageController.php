<?php namespace Indoraptor\Localization;

use Indoraptor\Content\LookupModel;

class LanguageController extends \Indoraptor\IndoController
{
    public function index()
    {
        $this->connect();
        
        $from = $this->getParam('from');
        $model = new LanguageModel($this->conn);
        $record = $model->retrieve($from ?? 'common');
        
        if ($record) {
            return $this->success($record);
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function copyTranslation()
    {
        $this->connect();
        
        if ($this->accept()) {
            $payload = $this->payload(true);
            $a = $payload['from'] ?? false;
            $b = $payload['to'] ?? false;
            if ($a && $b) {
                $translated = [];

                $translation = new TranslationModel($this->conn);
                $tnames = $translation->getTranslationNames();
                foreach ($tnames as $table) {
                    $translation->setTables($table);
                    $translation->copy($a, $b);
                    $translated[] = "translation_$table";
                }

                $lookup = new LookupModel($this->conn);
                $lookups = $lookup->getLookupNames();
                $lookup_ignores = array('country');
                foreach ($lookups as $reference) {
                    if ( ! \in_array($reference, $lookup_ignores)) {
                        $lookup->setTables($reference);
                        $lookup->copy($a, $b);
                        $translated[] = "lookup_$reference";
                    }
                }
            }
        }
        
        if (isset($translated)) {
            return $this->success($translated);
        } else {
            return $this->error('Not Found');            
        }        
    }
}
