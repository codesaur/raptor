<?php namespace Velociraptor\Account;

use codesaur as single;
use codesaur\Globals\Get;
use codesaur\Globals\Post;
use codesaur\Base\LogLevel;
use codesaur\Globals\Server;

use Velociraptor\DashboardController;

class LoginController extends DashboardController
{
    public function frontend()
    {
        single::header()->location(single::app()->getWebUrl(false));
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
        
        $orgs_names = $this->indoget('/account/get/organizations/names');
        $vars['organizations_names'] = $orgs_names['data'] ?? array();
        
        $template = $this->getTemplate(null, null, $vars);
        $template->getContent()->file($template->getSourceFolder() . '/login.html');
        $template->render();
    }
    
    public function entry()
    {
        $logdata = array();
        
        try {
            $post = new Post();
            if ( ! $post->hasArray(array('username', 'password'))) {
                throw new \Exception(single::text('invalid-request'));
            }

            $credintials = array(
                'username' => $post->value('username'),
                'password' => $post->value('password'));
            $logdata['username'] = $credintials['username'];
            $response = $this->indopost('/auth/try', $credintials);
            if ( ! isset($response['data']['account']['jwt'])) {
                throw new \Exception($response['error']['message'] ?? single::text('invalid-response!'));
            }
            
            single::session()->set('indo/jwt', $response['data']['account']['jwt']);
            
            single::response()->json(array(
                'type' => 'success', 'message' => 'success', 'url' => single::link('home')));

            $logdata['message'] =
                    "Хэрэглэгч {$response['data']['account']['first_name']} " .
                    "{$response['data']['account']['last_name']} системд нэвтрэв.";
            $logdata['account'] = $response['data']['account'];

            if (empty($response['data']['account']['code'])) {
                $this->indoput('/record?model='
                        . \urlencode('Indoraptor\\Account\\AccountModel'),
                        array('record' => array(
                            'code' => single::language()->current(),
                            'id' => $response['data']['account']['id'])));                
            } elseif ($response['data']['account']['code'] != single::language()->current()) {
                if (single::language()->select($response['data']['account']['code'])) {
                    single::session()->set(single::app()->getNamespace() .'Language', $response['data']['account']['code']);
                }
            }
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            $reason = 'attempt';
            $logdata['message'] = $e->getMessage();
            
            if (single::session()->check('indo/jwt')) {
                single::session()->release('indo/jwt');
            }
            
            single::response()->json(array('type' => 'danger', 'message' => $logdata['message']));
        } finally {
            if (isset($response['data']['account']['id'])) {
                $logdata['created_by'] = $response['data']['account']['id'];
                $logdata['address'] = (new Server())->determineIP();
            }
                
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
            $current_org_id = single::user()->organization('id');
            
            $organizations_result = $this->indopost('/statement', array(
                'sql' =>
                'SELECT t2.id, t2.name, t2.logo, t2.alias ' .
                'FROM organization_users as t1 JOIN organizations as t2 ON t1.organization_id = t2.id ' .
                'WHERE t1.account_id = :account_id AND t2.id=:id AND t1.is_active = 1 AND t2.is_active = 1 LIMIT 1',
                'bind' => array(':account_id' => array('variable' => $account_id), ':id' => array('variable' => $id)))
            );
            
            if (isset($organizations_result['data'][0])
                    || single::user()->is('system_coder')) {
                $jwt_info = array(
                    'organization_id' => (int)$id,
                    'account_id' => (int)$account_id);
                $jwt_result = $this->indopost('/auth/jwt/org', $jwt_info);
                
                if (isset($jwt_result['jwt']) && ! $this->isEmpty($jwt_result['jwt'])) {
                    single::session()->set('indo/jwt', $jwt_result['jwt']);

                    $this->log(
                        'organization',
                        array(
                            'message' =>
                            'Хэрэглэгч ' . single::user()->account('first_name') . ' ' .
                            single::user()->account('last_name') . ' нэвтэрсэн байгууллага сонгов.',
                            'jwt-info' => $jwt_info, 'enter' => $id, 'leave' => $current_org_id
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
            $response = array('data' => array('message' => single::text('invalid-request')));
        } else {
            $username = $post->value('codeUsername');
            $email = $post->value('codeEmail', FILTER_SANITIZE_EMAIL);
            $password = $post->asPassword($post->value('codePassword'));
            $payload = array(
                'flag' => single::language()->current(),
                'username' => $username, 'password' => $password,
                'email' => $email, 'organization_name' => $post->value('organization_name'));
            
            $response = $this->indopost('/account/signup', $payload);
        }
        
        single::response()->json(array(
            'type' => isset($response['data']['id']) ? 'success' : 'danger',
            'message' => $response['data']['message'] ?? single::text('something-went-wrong')));
    }

    public function requestPassword()
    {
        $post = new Post();
        if ($post->has('codeForgetEmail')) {
            $email = $post->value('codeForgetEmail', FILTER_SANITIZE_EMAIL);
        }
        
        $payload = array(
            'email' => $email, 'flag' => single::language()->current(),
            'login' => single::request()->getHttpHost() . single::link('login'));
        $response = $this->indopost('/account/forgot', $payload);
        
        single::response()->json(array(
            'type' => isset($response['data']['forgot']) ? 'success' : 'danger',
            'message' => $response['data']['message'] ?? single::text('something-went-wrong')));
    }
    
    public function resetPassword($id, $error = null)
    {
        $response = $this->indopost('/account/get/forgot', array('use' => $id));
        
        if ( ! isset($response['data'])) {
            $this->log('reset-password', array('message' =>
                'Хуурамч мэдээлэл ашиглан нууц үг тааруулахыг оролдов. Татгалзав.',
                'use_id' => $id), LogLevel::Security, 'account', 9);

            return single::redirect('home');
        }
        
        if ($response['data']['flag'] != single::language()->current()) {
            if (single::language()->select($response['data']['flag'])) {
                single::session()->set(single::app()->getNamespace() .'Language', $response['data']['flag']);
                return single::header()->redirect(single::link('login') . "?forgot=$id");
            }
        }

        $template = $this->getTemplate();
        
        if ($this->isNotExpired($response['data']['created_at'])) {
            $this->log('reset-password', array(
                'message' => 'Нууц үгийг шинээр тохируулж эхэллээ.',
                'forgot' => $response['data']), LogLevel::Security, 'account', 9);
            
            $template->title(single::text('set-new-password'));
            $template->getContent()->set('use_id', $id);
            $template->getContent()->set('account', $response['data']['account']);
            $template->getContent()->set('created_at', $response['data']['created_at']);
            
            if (isset($error)) {
                $template->getContent()->set('error', $error);
            }
            
            $template->getContent()->file($template->getSourceFolder() . '/login-reset-password.html');
        } else {
            $this->log('reset-password', array('message' =>
                'Хугацаа дууссан код ашиглан нууц үг шинээр тааруулахыг хүсэв. Татгалзав.',
                'forgot' => $response['data']), LogLevel::Security, 'account', 9);
            
            $template->title(single::text('failure'));
            $template->getContent()->file($template->getSourceFolder() . '/login-forgot.html');
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
        
        $response = $this->indopost('/account/get/forgot', array('use' => $post->value('use_id')));
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
        
        $result = $this->indopost('/account/set/password', $payload);
        if ( ! isset($result['data'])) {
            return $this->resetPassword($post->value('use_id'), single::text('invalid-request'));
        }
        
        $this->log('reset-password', array(
            'message' => 'Нууц үг шинээр тохируулав.',
            'account' => $result['data'],
            'use_id' => $post->value('use_id')), LogLevel::Security, 'account', 9);
        
        $template = $this->getTemplate(single::text('success'));
        $template->getContent()->set('title', single::text('success'));
        $template->getContent()->set('notice', single::text('set-new-password-success'));
        $template->getContent()->set('btn', single::text('login'));
        $template->getContent()->set('btn_class', 'primary btn-lg btn-block');
        $template->getContent()->file($template->getSourceFolder() . '/login-forgot.html');
        
        $template->render();
    }
    
    public function isNotExpired($date, $minutes = 20)
    {
        $now_date = new \DateTime();
        $then = new \DateTime($date);
        $diff = $then->diff($now_date);
        
        return $diff->y == 0 && $diff->m == 0  && $diff->d == 0 && $diff->h == 0 && $diff->i < $minutes;
    }
}
