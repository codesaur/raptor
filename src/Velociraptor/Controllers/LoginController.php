<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\Globals\Get;
use codesaur\Globals\Post;
use codesaur\Generic\LogLevel;

use Velociraptor\Templates\Boot4\Login;

class LoginController extends RaptorController
{
    function __construct()
    {
        parent::__construct();
        
        $this->getTranslation('account');
    }

    public function frontend()
    {
        single::header()->location(single::app()->webUrl(false));
    }

    public function index()
    {
        $get = new Get();
        if ($get->has('forgot')) {
            return $this->resetPassword($get->value('forgot'));
        }

        if (single::user()->isLogin()) {
            return single::redirect('home');
        }

        $templates = $this->indopost('/content',
                array('table' => 'templates', '_keyword_' => array('tos', 'pp')));
        $vars = $templates['data'] ?? array();        

        ($this->getTemplate($vars))->render();
    }
    
    public function getTemplate(array $vars = [])
    {
        $template = single::app()->getNamespace() . 'Templates\\LoginTemplate';
        return \class_exists($template) ? new $template($vars) : new Login($vars);
    }
    
    public function entry()
    {
        $logdata = array();
        
        try {
            $post = new Post();
            if ( ! $post->hasArray(array('username', 'password'))) {
                throw new \Exception('invalid request');
            }

            $credintials = array(
                'username' => $post->value('username'),
                'password' => $post->value('password'));
            $logdata['username'] = $credintials['username'];
            $response = $this->indopost('/auth/try', $credintials);
            if ( ! isset($response['data']['account']['jwt'])) {
                throw new \Exception($response['error']['message'] ?? 'invalid response');
            }
            
            single::session()->set('indo/jwt', $response['data']['account']['jwt']);
            
            $image = (new ImageController())->setTable('accounts');
            $photo = $image->singlePath($response['data']['account']['id']);
            $picture_path = "dashboard/account/{$response['data']['account']['id']}/picture";
            if ($photo) {
                 single::session()->set($picture_path, $photo);
            } elseif (single::session()->check($picture_path)) {
                single::session()->release($picture_path);
            }
            
            single::response()->json(array(
                'type' => 'success', 'message' => 'success', 'url' => single::link('home')));

            $logdata['message'] =
                    "Хэрэглэгч {$response['data']['account']['first_name']} " .
                    "{$response['data']['account']['last_name']} системд нэвтрэв.";
            $logdata['account'] = $response['data']['account'];

            if (empty($response['data']['account']['code'])) {
                $this->indoput('/record?model='
                        . \urlencode('Indoraptor\\Models\\Accounts'),
                        array('record' => array(
                            'code' => single::flag(),
                            'id' => $response['data']['account']['id'])));                
            } elseif ($response['data']['account']['code'] != single::flag()) {
                single::language()->select($response['data']['account']['code']);
            }
        } catch(\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            $reason = 'attempt';
            switch ($e->getMessage()) {
                case 'inactive user': $logdata['message'] = single::text('error-account-inactive'); break;
                case 'invalid request': { $type = 'warning'; $logdata['message'] = single::text('enter-username-password'); } break;
                case 'account not found': case 'invalid password': $logdata['message'] = single::text('error-incorrect-credentials'); break;
                default: { $type = 'warning'; $logdata['message'] = single::text('something-went-wrong'); } break;
            }
            
            if (single::session()->check('indo/jwt')) {
                single::session()->release('indo/jwt');
            }
            
            single::response()->json(array(
                'type' => $type ?? 'danger', 'message' => $logdata['message']));
        } finally {
            $this->log($reason ?? 'login', $logdata, LogLevel::Security);
        }
    }

    public function logout()
    {
        if (single::user()->isLogin()) {
            $this->log(
                    'logout',
                    array(
                        'message' =>
                        'Хэрэглэгч ' . single::user()->account('first_name') . ' ' .
                        single::user()->account('last_name') . ' системээс гарлаа.',
                        'jwt' => single::session()->get('indo/jwt')
                    ),
                    LogLevel::Security
            );

            single::user()->logout();
        }
        
        if (single::session()->check('indo/jwt')) {
            single::session()->release('indo/jwt');
        }
        
        single::redirect('home');
    }
    
    public function selectOrganization($id)
    {
        $account_id = single::user()->account('id');
        
        if ($account_id && $id && single::user()->isLogin()
                && $id != single::user()->organization('id')) {
            $organizations_result = $this->indopost('/statement', array(
                'sql' =>
                'SELECT t2.id, t2.name, t2.logo, t2.alias ' .
                'FROM organization_users as t1 JOIN organizations as t2 ON t1.organization_id = t2.id ' .
                'WHERE t1.account_id = :account_id AND t2.id=:id AND t1.is_active = 1 AND t2.is_active = 1 LIMIT 1',
                'bind' => array(':account_id' => array('variable' => $account_id), ':id' => array('variable' => $id)))
            );
            
            if (isset($organizations_result['data'][0])) {
                $jwt_info = array(
                    'organization_id' => (int)$id,
                    'account_id' => (int)$account_id);
                
                $jwt_result = $this->indopost('/generate/jwt', $jwt_info);
                
                if (isset($jwt_result['jwt']) && ! $this->isEmpty($jwt_result['jwt'])) {
                    single::session()->set('indo/jwt', $jwt_result['jwt']);

                    $this->log(
                        'organization',
                        array(
                            'message' =>
                            'Хэрэглэгч ' . single::user()->account('first_name') . ' ' .
                            single::user()->account('last_name') . ' нэвтэрсэн байгууллага сонгов.',
                            'jwt-info' => $jwt_info,
                            'enter' => $organizations_result['data'][0],
                            'leave' => single::user()->organization()
                        ),
                        LogLevel::Security
                    );
                }
            }
        }
        
        single::redirect('home');
    }
    
    public function signup()
    {
        $post = new Post();
        if ( ! $post->hasArray(array('codeUsername', 'codeEmail', 'codePassword', 'codeRePassword'))
                || $post->value('codePassword') != $post->value('codeRePassword')) {
            $notice = single::text('invalid-request');
        } else {
            $username = $post->value('codeUsername');
            $email = $post->value('codeEmail', FILTER_SANITIZE_EMAIL);
            $password = $post->asPassword($post->value('codePassword'));
            $payload = array(
                'email' => $email, 'flag' => single::flag(),
                'username' => $username, 'password' => $password);
            $response = $this->indopost('/auth/signup', $payload);
            unset($payload['password']);
        }
        
        if (isset($response['data']['error'])) {
            switch ($response['data']['error']) {
                case 'mailer': $notice = single::text('emailer-not-set'); break;
                case 'template': $notice = single::text('email-template-not-set'); break;
                case 'email': {
                    $notice = single::text('account-email-exists');
                    $log = array('message' => "Бүртгэлтэй [$email] хаягаар шинэ хэрэглэгч үүсгэх хүсэлт ирүүллээ. Татгалзав.", 'error' => 'email');
                } break;
                case 'username': {
                    $notice = single::text('account-exists');
                    $log = array('message' => "Бүртгэлтэй [$username] хэрэглэгчийн нэрээр шинэ хэрэглэгч үүсгэх хүсэлт ирүүллээ. Татгалзав.", 'error' => 'username');
                } break;
                case 'newbie': {
                    $notice = single::text('account-request-exists');
                    $log = array('message' => "Шинээр $username нэртэй [$email] хаягтай хэрэглэгч үүсгэх хүсэлт ирүүлсэн боловч, уг мэдээллээр урьд нь хүсэлт өгч байсныг бүртгэсэн байсан учир дахин хүсэлт бүртгэхээс татгалзав.", 'error' => 'newbie');
                } break;
                case 'payload': $notice = single::text('invalid-request'); break;
            }
        } elseif (isset($response['data']['id'])) {
            $success = true;
            $notice = single::text('to-complete-registration-check-email');
            $log = array(
                'id' => $response['data']['id'],
                'message' => "Шинээр $username нэртэй [$email] хаягтай хэрэглэгч үүсгэх хүсэлт ирүүлснийг хүлээн авч амжилттай бүртгэв."
            );
        }
        
        if (isset($log)) {
            $log['payload'] = $payload ?? null;
            $this->log('request-new-account', $log, LogLevel::Security, 'account', 9);
        }
        
        single::response()->json(array(
            'type' => $success ?? false ? 'success' : 'danger',
            'message' => $notice ?? single::text('something-went-wrong')));
    }

    public function requestPassword()
    {
        $post = new Post();
        if ($post->has('codeForgetEmail')) {
            $email = $post->value('codeForgetEmail', FILTER_SANITIZE_EMAIL);
        }
        
        $payload = array(
            'email' => $email, 'flag' => single::flag(),
            'login' => single::request()->getHttpHost() . single::link('login'));
        $response = $this->indopost('/auth/forgot', $payload);
        
        if (isset($response['data']['error'])) {
            switch ($response['data']['error']) {
                case 'mailer': $notice = single::text('emailer-not-set'); break;
                case 'template': $notice = single::text('email-template-not-set'); break;
                case 'inactive': {
                    $notice = single::text('error-account-inactive');
                    $log = array('message' => "Эрх нь нээгдээгүй хэрэглэгч [$email] нууц үг шинэчлэх хүсэлт илгээх оролдлого хийв. Татгалзав.", 'error' => 'inactive');
                } break;
                case 'account': {
                    $notice = single::text('account-did-not-exists');
                    $log = array('message' => "Бүртгэлгүй [$email] хаяг дээр нууц үг шинээр тааруулах хүсэлт илгээхийг оролдлоо. Татгалзав.", 'error' => 'account');
                } break;
                case 'invalid': $notice = single::text('invalid-request'); break;                
            }
        } elseif (isset($response['data']['forgot'])) {
            $success = true;
            $notice = single::text('reset-email-sent');
            $log = array(
                'message' => "Хэрэглэгч {$response['data']['forgot']['first_name']} {$response['data']['forgot']['last_name']} [$email] нь нууц үгийг шинээр тааруулах хүсэлт илгээснийг зөвшөөрлөө.",
                'forgot'  => $response['data']['forgot']
            );
        }
        
        if (isset($log)) {
            $log['payload'] = $payload ?? null;
            $this->log('request-password', $log, LogLevel::Security, 'account', 9);
        }
        
        single::response()->json(array(
            'type' => $success ?? false ? 'success' : 'danger',
            'message' => $notice ?? single::text('something-went-wrong')));
    }
    
    public function resetPassword($id, $error = null)
    {
        $response = $this->indopost('/auth/get/forgot', array('use' => $id));
        
        if ( ! isset($response['data'])) {
            $this->log('reset-password', array('message' =>
                'Хуурамч мэдээлэл ашиглан нууц үг тааруулахыг оролдов. Татгалзав.',
                'use_id' => $id), LogLevel::Security, 'account', 9);

            return single::redirect('home');
        }
        
        if ($response['data']['flag'] != single::flag()) {
            if (single::language()->select($response['data']['flag'])) {
                return single::header()->redirect(single::link('login') . "?forgot=$id");
            }
        }

        $template = $this->getTemplate();
        
        if ($this->isNotExpired($response['data']['created_at'])) {
            $this->log('reset-password', array(
                'message' => 'Нууц үгийг шинээр тохируулж эхэллээ.',
                'forgot' => $response['data']), LogLevel::Security, 'account', 9);
            
            $template->title(single::text('set-new-password'));
            $template->setContentVar('use_id', $id);
            $template->setContentVar('account', $response['data']['account']);
            $template->setContentVar('created_at', $response['data']['created_at']);
            
            if (isset($error)) {
                $template->setContentVar('error', $error);
            }
            
            $template->get('content')->file(velociraptor_boot4 . '/login-reset-password.html');
        } else {
            $this->log('reset-password', array('message' =>
                'Хугацаа дууссан код ашиглан нууц үг шинээр тааруулахыг хүсэв. Татгалзав.',
                'forgot' => $response['data']), LogLevel::Security, 'account', 9);
            
            $template->title(single::text('failure'));
            $template->get('content')->file(velociraptor_boot4 . '/login-forgot.html');
        }
        
        $template->render();
    }
    
    public function setPassword()
    {
        $post = new Post();
        if ( ! $post->hasArray(array(
            'use_id', 'account', 'created_at', 'password_new', 'password_retype'))) {
            return single::redirect('home');
        }
        
        $response = $this->indopost('/auth/get/forgot', array('use' => $post->value('use_id')));
        if ( ! isset($response['data']) ||
                $response['data']['account'] != $post->value('account')) {
            return $this->resetPassword($post->value('use_id'), single::text('invalid-request'));
        }

        $payload = array(
            'use_id' => $post->value('use_id'),
            'created_at' => $post->value('created_at'),
            'account' => $post->value('account', FILTER_VALIDATE_INT),
            'password' => $post->asPassword($post->value('password_new'))
        );
        
        if (empty($post->value('password_new')) ||
                $post->value('password_new') != $post->value('password_retype')) {
            $this->log('reset-password', array('message' =>
                'Нууц үг шинээр тохируулах үйлдэл амжилтгүй боллоо. Шалтгаан: Шинэ нууц үгээ буруу бичсэн.',
                'payload' => $payload), LogLevel::Security, 'account', 9);
            return $this->resetPassword($post->value('use_id'), single::text('password-must-match'));
        }
        
        $result = $this->indopost('/auth/set/password', $payload);
        if ( ! isset($result['data'])) {
            return $this->resetPassword($post->value('use_id'), single::text('invalid-request'));
        }
        
        $this->log('reset-password', array(
            'message' => 'Нууц үг шинээр тохируулав.',
            'account' => $result['data'],
            'use_id' => $post->value('use_id')), LogLevel::Security, 'account', 9);
        
        $template = $this->getTemplate();
        $template->title(single::text('success'));
        $template->setContentVar('title', single::text('success'));
        $template->setContentVar('notice', single::text('set-new-password-success'));
        $template->setContentVar('btn', single::text('login'));
        $template->setContentVar('btn_class', 'primary btn-lg btn-block');
        $template->get('content')->file(velociraptor_boot4 . '/login-forgot.html');
        
        $template->render();
    }
    
    public function isNotExpired($date, $minutes = 20)
    {
        $then = new \DateTime($date);
        $now_date =  new \DateTime();        
        $diff = $then->diff($now_date);
        
        return $diff->y == 0 && $diff->m == 0  && $diff->d == 0 && $diff->h == 0 && $diff->i < $minutes;
    }
}
