<?php namespace Velociraptor;

use codesaur as single;
use codesaur\HTML\Template;

class ErrorController extends \codesaur\Http\Controller
{
    public function error(string $message, int $status)
    {
        $vars = array('caption' => 'Home', 'home' => single::link('home'));
        
        if (DEBUG) {
            \error_log("Error[$status]: $message");

            $vars['message'] = "<hr><div style=\"text-align:center;color:white\">$message</div>";
        } else {
            $vars['message'] = '';
        }
        
        (new Template(\dirname(__FILE__) . '/error404.htm', $vars))->render();
    }
}
