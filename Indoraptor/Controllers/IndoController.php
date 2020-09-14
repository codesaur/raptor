<?php namespace Indoraptor\Controllers;

use codesaur as single;

use codesaur\Globals\Post;
use codesaur\Globals\Server;

use codesaur\Http\Header;
use codesaur\Http\Controller;

use codesaur\DataObject\MySQL;
use codesaur\DataObject\Table;

use codesaur\MultiModel\MultiModel;

use Indoraptor\Models\Mailer;

use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;

define('INDO_JWT_ALGORITHM', \getenv('INDO_JWT_ALGORITHM') ?: 'HS256');
define('INDO_JWT_LIFETIME',  \getenv('INDO_JWT_LIFETIME') ?: 2592000);
define('INDO_JWT_SECRET',    \getenv('INDO_JWT_SECRET') ?: 'codesaur-indoraptor-not-so-secret');

class IndoController extends Controller
{
    public $conn;
    protected $single;
    
    private $_header;
    private $_params;
    private $_payload;
    
    function __construct(bool $single = true)
    {
        $this->single = $single;
    }

    final function connect($halt = true)
    {
        if (isset($this->conn)) {
            return true;
        }
        
        $configuration = array(
            'driver'    => \getenv('DB_DRIVER') ?: 'mysql',
            'host'      => \getenv('DB_HOST') ?: 'localhost',
            'username'  => \getenv('DB_USERNAME') ?: 'root',
            'password'  => \getenv('DB_PASSWORD') ?: '',
            'name'      => \getenv('DB_NAME') ?: 'indoraptor',
            'engine'    => \getenv('DB_ENGINE') ?: 'InnoDB',
            'charset'   => \getenv('DB_CHARSET') ?: 'utf8',
            'collation' => \getenv('DB_COLLATION') ?: 'utf8_unicode_ci',
            'options'   => array(
                \PDO::ATTR_ERRMODE     => DEBUG ?
                \PDO::ERRMODE_EXCEPTION : \PDO::ERRMODE_WARNING,
                \PDO::ATTR_PERSISTENT  => \getenv('DB_PERSISTENT') == 'true'
            )
        );
        
        $this->conn = new MySQL($configuration);
        
        if ($this->conn->alive()) {
            if (\getenv('TIME_ZONE_UTC')) {
                $this->conn->exec('SET time_zone = ' . $this->conn->quote(\getenv('TIME_ZONE_UTC')));
            }
        } elseif ($halt) {
            return $this->error('CDO: Invalid configuration!', 700);
        }
        
        return $this->conn->alive();
    }

    final function respond($content, int $status = Header::HTTP_OK)
    {
        if ($this->single) {
            \header('Content-Type: application/json');
            
            if ($status >= 400 && $status < 600)  {
                \header("Status: Error $status", true, $status);
            }
            
            exit(\json_encode($content));
        }
        
        echo \json_encode($content);
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
    
    final public function getJWT()
    {
        if ($this->single) {
            $this->error('Not allowed!');
        }
        
        $payload = $this->payload(true);
        
        if (isset($payload['account_id']) && \is_int($payload['account_id']) &&
                isset($payload['organization_id']) && \is_int($payload['organization_id'])) {
            $account_org_jwt = array(
                'account_id' => $payload['account_id'],
                'organization_id' => $payload['organization_id']);
            return $this->respond(array('jwt' => $this->generate($account_org_jwt)));
        }
        
        $this->error('Invalid request!');
    }
    
    final public function accept()
    {
        if ($this->single) {
            $server = new Server();
            if ($server->has('HTTP_JWT')) {
                return \is_array($this->validate($server->raw('HTTP_JWT')));
            } elseif (single::session()->check('indo/jwt')) {
                return \is_array($this->validate(single::session()->get('indo/jwt')));
            }
        } else {
            if (isset($this->_header['HTTP_JWT'])) {
                return \is_array($this->validate($this->_header['HTTP_JWT']));
            }
        }
        
        return false;
    }
    
    final public function payload(bool $assoc = false, int $depth = 512, int $options = 0)
    {
        if ($this->single) {
            return single::header()->getEntityBodyJson($assoc, $depth, $options);
        } else {
            return \json_decode($this->_payload, $assoc, $depth, $options);
        }
    }
    
    final public function query()
    {
        if ($this->accept()) {
            $payload = $this->payload();
            if (isset($payload->sql)) {
                $this->connect();
                
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
                $this->connect();
                
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
        if ($this->single) {
            if (single::request()->hasParam($param)) {
                return single::request()->getParam($param);
            }
        } elseif (isset($this->_params[$param])) {
            return $this->_params[$param];
        }
        
        return null;
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
        if ($this->single) {
            $post = new Post();
            if ($post->has('alias')) {
                return $post->value('alias', FILTER_SANITIZE_STRING);
            }            
            if (single::request()->hasParam('table')) {
                return \str_replace(' ', '', single::request()->getParam('table'));
            }
        } elseif (isset($this->_params['table'])) {
            return \str_replace(' ', '', $this->_params['table']);
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
        
        $this->connect();
        
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
    
    public function getMailer()
    {
        $model = new Mailer($this->conn);
        $rows = $model->getRows();
        $record = \end($rows);
        if (empty($record)) {
            return null;
        }

        $mailer = new PHPMailer(false);
        if (((int) $record['is_smtp']) == 1) {
           $mailer->IsSMTP(); 
        }
        $mailer->CharSet = $record['charset'];
        $mailer->SMTPAuth = (bool)((int) $record['smtp_auth']);
        $mailer->SMTPSecure = $record['smtp_secure'];
        $mailer->Host = $record['host'];
        $mailer->Port = $record['port'];            
        $mailer->Username = $record['username'];
        $mailer->Password = $record['password'];
        $mailer->SetFrom($record['email'], $record['name']);
        $mailer->AddReplyTo($record['email'], $record['name']);
        $mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mailer;
    }
    
    final public function view()
    {
        single::header()->redirect(single::app()->webUrl(false) . '/dashboard/indoraptor');
    }
}
