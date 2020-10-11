<?php namespace Velociraptor\Boot4Template;

class Login extends Boot4
{
    function __construct(array $vars = [])
    {
        parent::__construct(\dirname(__FILE__) . '/login.html', $vars);
    }
}
