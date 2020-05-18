<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../mkdcore/builder/Draft_builder.php';

$raw_configuration = file_get_contents('configuration.json');

$config = json_decode($raw_configuration, TRUE);

if (file_exists('env.json')) {
    $env_configuration = file_get_contents('env.json');
    $env = json_decode($env_configuration, TRUE);
    $config['config'] = $env['config'];
    $config['database'] = $env['database'];
    $raw_configuration = json_encode($config);
}

$builder = new Draft_builder($raw_configuration);
$builder->build();