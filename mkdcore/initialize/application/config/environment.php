<?php

if(! defined('ENVIRONMENT') )
{
$domain = strtolower($_SERVER['HTTP_HOST']);

switch($domain) {
case 'mysite.com' :
define('ENVIRONMENT', 'production');
break;

case 'stage.mysite.com' :
//our staging server
define('ENVIRONMENT', 'staging');
break;

default :
define('ENVIRONMENT', 'development');
break;
}
}