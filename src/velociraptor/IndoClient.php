<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Http\Route;
use codesaur\Http\Router;
use codesaur\Http\Client;
use codesaur\Http\Request;
use codesaur\Base\OutputBuffer;

class IndoClient
{
    public $router;
    public $routing;
    
    function __construct()
    {
        $indoNamespace = single::app()->getConfiguraton()['/indo'] ?? null;
        
        if (isset($indoNamespace)
                && \class_exists($indoNamespace . 'Routing')) {
            $indoRoutingCls = $indoNamespace . 'Routing';
            $this->routing = new $indoRoutingCls();
        } else {
            $this->routing = new \Indoraptor\Routing();
        }
        
        $this->router = new Router();
        $this->routing->collect($this->router);
    }
    
    public function uri($url, bool $relative = false) : string
    {
        return single::app()->getWebUrl($relative) . "/indo/$url";
    }

    public function link($route, array $params = []) : string
    {
        $url = $this->router->generate($route, $params);
        
        if (empty($url)) {
            return 'indo-link-invalid';
        }
        
        return $this->uri($url[0]);
    }
    
    public function internal(
            string $pattern, string $method,
            bool $json, $payload, array $header = [])
    {
        $buffer = new OutputBuffer();
        $buffer->start();
        
        try {
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
            
            $route = $this->router->match($url, $method);
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
            
            $class = new $controller(true);
            if ( ! $class instanceof \Indoraptor\IndoController) {
                throw new \Exception("$controller is not an Indoraptor controller!");
            }            

            if ( ! $class->isConnected()) {
                throw new \Exception('Not connected!');
            }

            if ( ! $class->hasMethod($action) || ! $class->isCallable($action)) {
                throw new \Exception("Action named $action is not part of $controller!");
            }
            
            $class->setHeader($header);
            $class->setPayload($payload);
            $class->setParams($params ?? array());
            
            if (empty($args)) {
                $class->$action();
            } else {
                \call_user_func_array(array($class, $action), $args);
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
    
    function request(string $pattern, string $method, $payload)
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
            
            return (new Client())->request($this->uri($pattern), $method, $data, $options);
        } catch (\Exception $e) {
            return \json_encode(array('error' => array(
                'code' => $e->getCode(), 'message' => $e->getMessage())));
        }
    }
}
