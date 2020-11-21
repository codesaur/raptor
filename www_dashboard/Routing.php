<?php namespace Test\Dashboard;

class Routing extends \Velociraptor\Routing
{
    function getHomeRules() : array
    {
        return array(
            ['', 'Test\\Dashboard\\DashboardController'],
            ['/home', 'Test\\Dashboard\\DashboardController', ['name' => 'home']]
        );
    }
}
