<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\HTML\Template;
use codesaur\Http\Controller;

class ErrorController extends Controller
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
        
        (new Template(\dirname(__FILE__) . '/../Templates/html/error.htm', $vars))->render();
    }
}
