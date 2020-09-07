<?php namespace Indoraptor\Controllers;

use Indoraptor\Models\Lookup;
use Indoraptor\Models\Language;
use Indoraptor\Models\Translation;

class LanguageController extends IndoController
{
    public function index()
    {
        $this->connect();
        
        $from = $this->getParam('from');
        $model = new Language($this->conn);
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

                $translation = new Translation($this->conn);
                $tnames = $translation->getTranslationNames();
                foreach ($tnames as $table) {
                    $translation->setTables($table);
                    $translation->copy($a, $b);
                    $translated[] = "translation_$table";
                }

                $lookup = new Lookup($this->conn);
                $lookups = $lookup->getLookupNames();
                $lookup_ignores = array('country');
                foreach ($lookups as $reference) {
                    if ( ! \in_array($reference, $lookup_ignores)) {
                        $lookup->setTables($reference);
                        $lookup->copy($a, $b);
                        $translated[] = "lookup_$reference";
                    }
                }
/*
                foreach (code::app()->getParams() as $param) {
                    foreach(\glob("application/{$param['path']}/models/*.*") as $file) {
                        $classname = $param['namespace'] . \basename($file, '.php');
                        if (\class_exists($classname)) {
                            $class = new $classname();
                            if ($class instanceof MultiModel) {
                                if ($class->getMeClean() != 'TranslationModel' &&
                                        $class->getMeClean() != 'LookupModel') {
                                    if (isset($class->content)) {
                                        if (\count($class->getColumns()) > 3) {
                                            $translated[] = $class->getMeClean();
                                            $class->copy($a, $b);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }*/
            }
        }
        
        if (isset($translated)) {
            return $this->success($translated);
        } else {
            return $this->error('Not Found');            
        }        
    }
}
