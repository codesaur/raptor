<?php namespace Indoraptor;

use codesaur as single;
use codesaur\Http\Header;
use codesaur\Globals\Post;
use codesaur\Globals\Server;
use codesaur\DataObject\Table;
use codesaur\MultiModel\MultiModel;

use Firebase\JWT\JWT;

define('INDO_JWT_ALGORITHM', \getenv('INDO_JWT_ALGORITHM') ?: 'HS256');
define('INDO_JWT_LIFETIME',  \getenv('INDO_JWT_LIFETIME') ?: 2592000);
define('INDO_JWT_SECRET',    \getenv('INDO_JWT_SECRET') ?: 'codesaur-indoraptor-not-so-secret');

class IndoController extends \codesaur\Http\Controller
{
    public $conn;
    
    private $_header;
    private $_params;
    private $_payload;
    
    private $_is_internal = false;
    
    function __construct(bool $internal = false)
    {
        $this->_is_internal = $internal;
        
        $this->conn = single::helper()->getPDO();        
        if ( ! $this->conn->alive()) {
            $this->error('CDO: Not connected!', 700);
        }
    }
    
    final public function isConnected()
    {
        return $this->conn->alive();
    }

    final function respond($content, int $status = Header::HTTP_OK)
    {
        if ($this->_is_internal) {
            echo \json_encode($content);
            return;
        }
        
        \header('Content-Type: application/json');

        if ($status >= 400 && $status < 600)  {
            \header("Status: Error $status", true, $status);
        }

        exit(\json_encode($content));
    }
    
    final public function success($data)
    {
        $this->respond(array('data' => $data));
    }
    
    final public function error(string $message, int $status = Header::HTTP_NOT_FOUND)
    {
        $this->respond(array('error' => array('code' => $status, 'message' => $message)), $status);
    }
    
    final public function setHeader(array $header)
    {
        $this->_header = $header;
    }

    final public function setPayload(array $payload, int $options = 0, int $depth = 512)
    {
        $this->_payload = \json_encode($payload, $options, $depth);
    }
    
    final public function setParams($params)
    {
        $this->_params = $params;
    }    

    final public function generate(array $data)
    {
        $issuedAt = \time();
        $expirationTime = $issuedAt + INDO_JWT_LIFETIME;
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ) + $data;
        $key = INDO_JWT_SECRET;
        $alg = INDO_JWT_ALGORITHM;
        
        return JWT::encode($payload, $key, $alg);
    }
    
    final public function validate($jwt, $secret = null, $algs = null)
    {
        $result = $this->decode($jwt,
                $secret ?? INDO_JWT_SECRET,
                $algs ?? array(INDO_JWT_ALGORITHM));
        if ($result['account_id'] ?? false &&
                ! \getenv(_ACCOUNT_ID_, true)) {
            \putenv(_ACCOUNT_ID_ . "={$result['account_id']}");
        }
        
        return $result;
    }
    
    final public function decode($jwt, $key, array $algs)
    {
        try {
            return (array) JWT::decode($jwt, $key, $algs);
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return $e->getMessage();
        }
    }
    
    final public function accept()
    {
        if ($this->_is_internal) {
            if (isset($this->_header['HTTP_JWT'])) {
                return \is_array($this->validate($this->_header['HTTP_JWT']));
            }
        } else {
            $server = new Server();
            if ($server->has('HTTP_JWT')) {
                return \is_array($this->validate($server->raw('HTTP_JWT')));
            } elseif (\getenv('INDO_JWT', true)) {
                return \is_array($this->validate(\getenv('INDO_JWT', true)));
            }
        }
        
        return false;
    }
    
    final public function payload(bool $assoc = false, int $depth = 512, int $options = 0)
    {
        if ($this->_is_internal) {
            return \json_decode($this->_payload, $assoc, $depth, $options);
        }
        
        return single::header()->getEntityBodyJson($assoc, $depth, $options);
    }
    
    final public function query()
    {
        if ($this->accept()) {
            $payload = $this->payload();
            if (isset($payload->sql)) {
                $pdostmt = $this->conn->prepare($payload->sql);
                $pdostmt->execute();
                $result = $pdostmt->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    return $this->success($result);
                }
            }
        }
        
        return $this->error('Not Found');
    }
    
    final public function status()
    {
        if ($this->accept()) {
            $payload = $this->payload();
            if (isset($payload->table)) {
                $result = $this->conn->status($payload->table);
                if ($result) {
                    return $this->success($result);
                }
            }
        }
        
        return $this->error('Not Found');
    }
    
    final public function getParam(string $param)
    {
        if ($this->_is_internal) {
            return $this->_params[$param] ?? null;
        }
        
        return single::request()->getParam($param);
    }

    public function grab(string $param, $arg = null)
    {
        $class = $this->getParam($param);
        if ( ! $this->isEmpty($class ?? null)) {
            return $this->loadClass($class, $arg);
        }

        return null;
    }
    
    public function grabtable()
    {
        if ($this->_is_internal) {
            if (isset($this->_params['table'])) {
                return \str_replace(' ', '', $this->_params['table']);
            }
        } else {
            $post = new Post();
            if ($post->has('alias')) {
                return $post->value('alias', FILTER_SANITIZE_STRING);
            }            
            if (single::request()->hasParam('table')) {
                return \str_replace(' ', '', single::request()->getParam('table'));
            }
        }
        
        return null;
    }
    
    final public function grabmodel(bool $strict = false)
    {
        $model = $this->grab('model', $this->conn);
        if (isset($model) && $model instanceof Table) {
            $table = $this->grabtable();
            if ( ! $this->isEmpty($table)) {
                if ($model instanceof MultiModel) {
                    $model->setTables($table);
                } else {
                    $model->setTable($table);
                }
            }
        } elseif ($strict) {
            return $this->error('Invalid request');
        }
        
        return $model;
    }
    
    public function statement()
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $model = $this->grabmodel();
        $payload = $this->payload(true);
        
        if ( ! isset($payload['sql'])) {
            return $this->error('Invalid Request');
        }
        
        if ($model) {
            $query = "SELECT * FROM {$model->getTable()} {$payload['sql']}";
        } else {
            $query = $payload['sql'];
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (isset($payload['bind'])) {
            foreach ($payload['bind'] as $parametr => $values) {
                if (isset($values['variable'])) {
                    if (isset($values['length'])) {
                        $stmt->bindParam($parametr, $values['variable'], $values['type'] ?? \PDO::PARAM_STR, $values['length']);
                    } else {
                        $stmt->bindParam($parametr, $values['variable'], $values['type'] ?? \PDO::PARAM_STR);
                    }
                }
            }
        }
        
        $stmt->execute();
        
        $result = array();
        if ($stmt->rowCount()) {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                if (isset($row['id'])) {
                    $result[$row['id']] = $row;
                } else {
                    $result[] = $row;
                }
            }
        }

        $this->success($result);
    }
    
    final public function view()
    {
        single::header()->redirect(single::app()->getWebUrl(false) . '/dashboard/indoraptor');
    }
}
