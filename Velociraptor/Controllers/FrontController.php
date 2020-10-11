<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\Http\Controller;

class FrontController extends BackController
{
    function __construct()
    {
        $this->initLanguage();
        $this->getTranslation(array('default', 'user'));
    }
    
    final public function initLanguage()
    {
        $languages = $this->indoget('/language');
        
        if (isset($languages['data'])) {
            single::language()->create($languages['data'], single::app()->getNamespace() . 'Language');
        }
        
        if ( ! single::language()->created()) {
            single::app()->error('Language initilization error!', 400);
        }
    }
    
    final public function changeLanguage($flag)
    {
        $location = single::request()->getHttpHost()
                . single::request()->getPathComplete();

        if (single::request()->hasParam('ulang')) {
            $location .= single::request()->getParam('ulang');
            foreach (single::request()->getParams() as $key => $value) {
                if ($key != 'ulang') {
                    $location .= "&$key=$value";
                }
            }
        }

        if (single::language()->select($flag) &&
                single::controller() instanceof Controller) {
            if (single::controller()->hasMethod('onChangeLanguage') &&
                    single::controller()->isCallable('onChangeLanguage')) {
                single::controller()->onChangeLanguage($flag);
            }
            single::header()->redirect($location);
        } else {
            single::redirect('home');
        }
    }

    final public function getTranslation($name, string $flag = null)
    {
        $translations = $this->indopost('/translation/retrieve', array(
            'table' => $name, 'flag' => $flag ?? single::language()->current()));

        if (isset($translations['data'])) {
            foreach ($translations['data'] as $name => $text) {
                single::translation()->append($name, $text);
            }
        }
    }
    
    final public function vardump($var, bool $full = true)
    {
        if (DEBUG) {
            $debug = \debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
            \var_dump(['file' => $debug[0]['file'] ?? '', 'line' => $debug[0]['line'] ?? '']);
            
            if ($full) {
                \var_dump($var);
            } elseif (\is_array($var)) {
                \print_r($var);
            } else {
                echo $var;
            }
        }
    }
}
