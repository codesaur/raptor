<?php namespace Velociraptor;

interface DashboardTemplateInterface
{
    function __construct($title = null, array $vars = []);
            
    public function title(string $value);
    public function breadcrumb($item);    
    public function alert($content, $icon = null, $class = null, $role = null);
    public function toolbar($text, $icon = null, $class = '', $href = 'javascript:;', $modal = null);
    public function callout($content, $type, $icon = null, $class = null, bool $close = true, $id = null);
    
    public function &get(string $key);
    public function &getContent() : ?TwigTemplate;

    public function render($content = null);
    
    public function alertErrorPermission($message = null, $icon = 'flaticon-security', $reload = true, $is_modal = false);
    public function addDeleteScript($data, $container = 'table', $question = null, $action = null, $crud = 'delete', $method = null, $selector = null);
}
