<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Base\LogLevel;
use codesaur\Globals\Server;

class Controller extends \codesaur\Http\Controller
{
    public $indo_client;
    
    final public function indo(
            string $pattern, string $method,
            bool $json, $payload, array $header = [])
    {
        if ( ! isset($this->indo_client)) {
            $this->indo_client = new IndoClient();
        }
        
        return $this->indo_client->internal($pattern, $method, $json, $payload, $header);
    }
    
    final public function indoget(string $pattern, $payload = [], bool $json = false)
    {
        return $this->indo($pattern, 'GET', $json, $payload);
    }
    
    final public function indopost(string $pattern, $payload = [], bool $json = false)
    {
        return $this->indo($pattern, 'POST', $json, $payload);
    }

    final public function indoput(string $pattern, $payload = [], bool $json = false)
    {
        return $this->indo($pattern, 'PUT', $json, $payload);
    }
    
    final public function indodelete(string $pattern, $payload = [], bool $json = false)
    {
        return $this->indo($pattern, 'DELETE', $json, $payload);
    }

    final public function indolog(
            string $reason, $data = null,
            int $level = LogLevel::Basic, 
            $table = null, $type = null, $time = null)
    {
        if (\is_array($data)) {
            $info = $data;
        } else {
            $info = array('message' => $data ?? '');
        }
        
        $info['flag'] = single::language()->current();
        $info['url'] = $data['url'] ?? single::request()->getUrl();
        $info['method'] = $data['method'] ?? single::request()->getMethod();
        $info['address'] = $data['address'] ?? (new Server())->determineIP();
        
        $payload = array(
            'level'  => $level,
            'info'   => \json_encode($info),
            'reason' => \str_replace('_', '-', \strtolower($reason))
        );
        
        if (isset($type)) { $payload['type'] = $type; }        
        if (isset($time)) { $payload['created_at'] = $time; }

        if (single::user()->isLogin()
                && single::user()->account('id') != null) {
            $payload['created_by'] = (int) single::user()->account('id');
        }
        
        if (empty($table)) {
            $table = single::request()->getParam('logger') ?? 'default';
        }
        
        $this->indopost("/log/$table", $payload);
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

        if (single::language()->select($flag)) {
            single::session()->set(single::app()->getNamespace() .'Language', $flag);
            
            if (single::controller() instanceof \codesaur\Http\Controller
                    && single::controller()->hasMethod('onChangeLanguage')
                    && single::controller()->isCallable('onChangeLanguage')) {
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
                single::translation()->create($name, $text);
            }
        }
    }
}
