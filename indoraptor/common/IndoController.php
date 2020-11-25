<?php namespace Indoraptor;

use codesaur as single;
use codesaur\Http\Header;
use codesaur\Globals\Post;
use codesaur\Globals\Server;
use codesaur\DataObject\MySQL;
use codesaur\DataObject\Table;
use codesaur\MultiModel\MultiModel;

use Firebase\JWT\JWT;

define('INDO_JWT_ALGORITHM', \getenv('INDO_JWT_ALGORITHM', true) ?: 'HS256');
define('INDO_JWT_LIFETIME',  \getenv('INDO_JWT_LIFETIME', true) ?: 2592000);
define('INDO_JWT_SECRET',    \getenv('INDO_JWT_SECRET', true) ?: 'codesaur-indoraptor-not-so-secret');

class IndoController extends \codesaur\Http\Controller
{
    public $conn;
    
    private $_header;
    private $_params;
    private $_payload;
    
    private $_is_internal;
    
    function __construct(bool $internal = false)
    {
        $this->_is_internal = $internal;
        
        try {
            $configuration = array(
                'driver'    => \getenv('DB_DRIVER', true) ?: 'mysql',
                'host'      => \getenv('DB_HOST', true) ?: 'localhost',
                'username'  => \getenv('DB_USERNAME', true) ?: 'root',
                'password'  => \getenv('DB_PASSWORD', true) ?: '',
                'name'      => \getenv('DB_NAME', true) ?: 'indoraptor',
                'engine'    => \getenv('DB_ENGINE', true) ?: 'InnoDB',
                'charset'   => \getenv('DB_CHARSET', true) ?: 'utf8',
                'collation' => \getenv('DB_COLLATION', true) ?: 'utf8_unicode_ci',
                'options'   => array(
                    \PDO::ATTR_ERRMODE     => DEBUG ?
                    \PDO::ERRMODE_EXCEPTION : \PDO::ERRMODE_WARNING,
                    \PDO::ATTR_PERSISTENT  => \getenv('DB_PERSISTENT', true) == 'true'
                )
            );

            $this->conn = new MySQL($configuration);
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
        
        if ($this->conn->alive()) {
            if (\getenv('TIME_ZONE_UTC', true)) {
                $this->conn->exec('SET time_zone = ' . $this->conn->quote(\getenv('TIME_ZONE_UTC', true)));
            }
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
    
    final public function validate($jwt = null, $secret = null, $algs = null)
    {
        if ( ! isset($jwt)) {
            if ($this->_is_internal) {
                if (isset($this->_header['HTTP_JWT'])) {
                    $jwt = $this->_header['HTTP_JWT'];
                }
            } else {
                $server = new Server();
                if ($server->has('HTTP_JWT')) {
                    $jwt = $server->raw('HTTP_JWT');
                } elseif (\getenv('INDO_JWT', true)) {
                    $jwt = \getenv('INDO_JWT', true);
                }
            }
        }
        
        try {
            if (empty($jwt)) {
                throw new \Exception('Undefined JWT!');
            }
            
            $result = (array) JWT::decode($jwt,
                    $secret ?? INDO_JWT_SECRET,
                    $algs ?? array(INDO_JWT_ALGORITHM));
            
            if ($result['account_id'] ?? false &&
                ! \getenv(_ACCOUNT_ID_, true)) {
                \putenv(_ACCOUNT_ID_ . "={$result['account_id']}");
            }
            
            return $result;            
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return $e->getMessage();
        }
    }
    
    final public function accept()
    {
        return \is_array($this->validate());
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

    public function email()
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $payload = $this->payload(true);
        if ( ! isset($payload['to'])
                || ! isset($payload['subject'])
                || ! isset($payload['message'])) {
            return $this->error('Invalid Request');
        }
        
        try {
            $this->sendEmail($payload['to'], $payload['name'] ?? '', $payload['subject'], $payload['message'], $payload['flag'] ?? $this->getAppLanguageCode());
            $this->success(array('message' => 'Email successfully sent to destination'));
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
    }
    
    public function sendEmail($to, $name, $subject, $message, $flag = 'en')
    {
        if (\getenv('MAIL_SENDER', true)) {
            $mail = new \codesaur\Base\Mail();
            $mail->sender = \getenv('MAIL_SENDER', true);
            $mail->to = $to;
            $mail->message = $message;
            $mail->subject = $subject;
            $mail->send();
        } else {
            $model = new Content\MailerModel($this->conn);
            $rows = $model->getRows();
            $record = \end($rows);

            if (empty($record) || ! isset($record['charset'])
                    || ! isset($record['host']) || ! isset($record['port'])
                    || ! isset($record['email']) || ! isset($record['name'])
                    || ! isset($record['username']) || ! isset($record['password'])
                    || ! isset($record['is_smtp']) || ! isset($record['smtp_auth']) || ! isset($record['smtp_secure'])) {
                $translation = new Localization\TranslationModel($this->conn);
                $translation->setTables('dashboard');
                $text = $translation->retrieve($flag);
                throw new \Exception($text['emailer-not-set'] ?? 'Email carrier not found!');
            }

            $mailer = new \PHPMailer\PHPMailer\PHPMailer(DEBUG ? true : null);
            if (((int) $record['is_smtp']) == 1) {
               $mailer->IsSMTP(); 
            }
            $mailer->Mailer = 'smtp';
            $mailer->CharSet = $record['charset'];
            $mailer->SMTPAuth = (bool)((int) $record['smtp_auth']);
            $mailer->SMTPSecure = $record['smtp_secure'];
            $mailer->Host = $record['host'];
            $mailer->Port = $record['port'];
            $mailer->Username = $record['username'];
            $mailer->Password = $record['password'];
            $mailer->SetFrom($record['email'], $record['name']);
            $mailer->AddReplyTo($record['email'], $record['name']);
            $mailer->SMTPOptions = array('ssl' => array(
                'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
            
            $mailer->MsgHTML($message);
            $mailer->Subject = $subject;
            $mailer->AddAddress($to, $name);
            $mailer->Send();
        }
    }
    
    final public function getAppLanguageCode()
    {
        if (single::language()->current()) {
            return single::language()->current();
        }
        
        $session_path = single::app()->getNamespace() . 'Language';
        if (isset($_SESSION[$session_path])) {
            return $_SESSION[$session_path];
        }
        
        return 'en';
    }
    
    final public function view()
    {
        single::header()->redirect(single::app()->getWebUrl(false) . '/dashboard/indoraptor');
    }
}
