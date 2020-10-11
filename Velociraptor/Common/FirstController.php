<?php namespace Velociraptor\Common;

use codesaur as single;
use codesaur\Http\Route;
use codesaur\Http\Router;
use codesaur\Http\Client;
use codesaur\Http\Request;
use codesaur\Globals\Server;
use codesaur\Http\Controller;
use codesaur\Common\LogLevel;
use codesaur\Common\OutputBuffer;

use Indoraptor\Common\IndoController;

class FirstController extends Controller
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
    
    public function indorouting()
    {
        try {
            return new \Indoraptor\Routing();
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return null;
        }
    }
    
    final public function indouri($url)
    {
        return single::app()->webUrl(false) . "/indo/$url";
    }

    final public function indolink($route, array $params = [])
    {
        $routing = $this->indorouting();
        if ($routing) {
            $router = new Router();
            $routing->collect($router);
            $url = $router->generate($route, $params);
        }
        
        if (empty($url)) {
            return 'javascript:;';
        }
        
        return $this->indouri($url[0]);
    }
    
    final function indo(
            string $pattern, string $method,
            bool $json, $payload, array $header = [])
    {
        $buffer = new OutputBuffer();
        $buffer->start();
        
        try {
            $routing = $this->indorouting();
            if ( ! $routing) {
                throw new \Exception('Indo Routing Not Found');
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
            
            $class = new $controller(false);
            if ( ! $class instanceof IndoController) {
                throw new \Exception("$controller is not an IndoController!");
            }            
            if ( ! $class->hasMethod($action) || ! $class->isCallable($action)) {
                throw new \Exception("Action named $action is not part of $controller!");
            }
            
            if (single::session()->check('indo/jwt')) {
                $header['HTTP_JWT'] = single::session()->get('indo/jwt');
            }
            $class->setHeader($header);
            $class->setPayload($payload);
            $class->setParams($params ?? array());

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
        
        $info['flag'] = single::flag();
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
        
        $this->indopost("/log/$table", $payload);
    }
    
    final function indorequest(string $pattern, string $method, $payload)
    {
        try {
            $client = new Client();
            
            $header = array();
            
            if (single::session()->check('indo/jwt')) {
                $header[] = 'JWT:' . single::session()->get('indo/jwt');
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
            
            return $client->request($this->indouri($pattern), $method, $data, $options);
        } catch (\Exception $e) {
            return \json_encode(array('error' => array(
                'code' => $e->getCode(), 'message' => $e->getMessage())));
        }
    }
    
    public function getMailer()
    {
        return (new IndoController())->getMailer();
    }
}
