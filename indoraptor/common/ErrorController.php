<?php namespace Indoraptor;

use codesaur\Http\Controller;

class ErrorController extends Controller
{
    public function error(string $message, int $status)
    {
        \header('Content-Type: application/json');        

        \error_log("Error[$status]: $message");
        
        echo \json_encode(array('error' => array('code' => $status, 'message' => $message)));
    }
}
