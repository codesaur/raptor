<?php

/* DEV: v7.2020.11.20
 * 
 * This is test script!
 */

$autoload = require_once '../vendor/autoload.php';
$autoload->addPsr4('Test\\Dashboard\\', \dirname(__FILE__));

codesaur::start(new Velociraptor\Application(array('/' => 'Test\\Dashboard\\')));
