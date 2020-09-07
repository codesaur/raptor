<?php namespace Velociraptor\Templates\Boot4;

class Login extends Boot4
{
    function __construct(array $vars = [])
    {
        parent::__construct(velociraptor_boot4 . '/login.html', $vars);
    }
}
