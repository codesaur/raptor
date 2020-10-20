<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Http\Route;
use codesaur\Http\Router;
use codesaur\Http\Client;
use codesaur\Http\Request;
use codesaur\Base\LogLevel;
use codesaur\Globals\Server;
use codesaur\Base\OutputBuffer;

class Controller extends \codesaur\Http\Controller
{
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
    
    final public function indouri($url, bool $relative = false) : string
    {
        return single::app()->getWebUrl($relative) . "/indo/$url";
    }

    final public function indolink($route, array $params = []) : string
    {
        $routing = new \Indoraptor\Routing();
        if ($routing) {
            $router = new Router();
            $routing->collect($router);
            $url = $router->generate($route, $params);
        }
        
        if (empty($url)) {
            return 'indo-link-invalid';
        }
        
        return $this->indouri($url[0]);
    }
    
    final public function indo(
            string $pattern, string $method,
            bool $json, $payload, array $header = [])
    {
        $buffer = new OutputBuffer();
        $buffer->start();
        
        try {
            $routing = new \Indoraptor\Routing();
            if ( ! $routing) {
                throw new \Exception('Indoraptor routing not found!');
            }
            
            $router = new Router();
            $routing->collect($router);
            $pos = \strpos($pattern, '?');
            if ($pos !== false) {
                $url = \substr($pattern, 0, $pos);
                $query = \substr($pattern, $pos + 1);
                
                $values = [];
                \parse_str($query, $values);
                
                $request = new Request();
                foreach ($values as $key => $value) {
                    $request->recursionParams($key, $value);
                }
                $params = $request->getParams();
            } else {
                $url = $pattern;
            }
            
            $route = $router->match($url, $method);
            if ( ! $route instanceof Route) {
                throw new \Exception('Unknown route!');
            }
            
            $action = $route->getAction();
            $args = $route->getParameters();
            $controller = $route->getController();
            if ( ! \class_exists($controller)) {
                throw new \Exception("$controller is not available!");
            }
            
            if (\getenv('INDO_JWT', true)) {
                $header['HTTP_JWT'] = \getenv('INDO_JWT', true);
            }
            
            $class = new $controller(false, $header, $params ?? array(), $payload);
            if ( ! $class instanceof \Indoraptor\IndoController) {
                throw new \Exception("$controller is not an Indoraptor controller!");
            }            
            if ( ! $class->hasMethod($action) || ! $class->isCallable($action)) {
                throw new \Exception("Action named $action is not part of $controller!");
            }
            
            if (empty($args)) {
                $class->$action();
            } else {
                $this->callFuncArray(array($class, $action), $args);
            }
        } catch (\Exception $e) {
            echo \json_encode(array('error' => array(
                'code' => 404, 'message' => $e->getMessage())));
        } finally {
            $result = $buffer->getContents();
            $buffer->end();
            
            return \json_decode($result, ! $json);
        }        
    }
    
    final function indorequest(string $pattern, string $method, $payload)
    {
        try {
            $header = array();
            
            if (\getenv('INDO_JWT', true)) {
                $header[] = 'JWT:' . \getenv('INDO_JWT', true);
            }
            
            if ($method != 'GET' && ! empty($payload)) {
                $data = \json_encode($payload);
                $header[] = 'Content-Type: application/json';
            } else {
                $data = '';
            }
            
            $options = array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => $header
            );
            
            return (new Client())->request($this->indouri($pattern), $method, $data, $options);
        } catch (\Exception $e) {
            return \json_encode(array('error' => array(
                'code' => $e->getCode(), 'message' => $e->getMessage())));
        }
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

        if (single::user()->isLogin() &&
                single::user()->has('account', 'id')) {
            $payload['created_by'] = (int) single::user()->account('id');
        }
        
        if (empty($table)) {
            $table = single::request()->getParam('logger') ?? 'default';
        }
        
        $this->indo("/log/$table", 'POST', false, $payload);
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
                single::translation()->append($name, $text);
            }
        }
    }
}
