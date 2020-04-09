<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../mkdcore/builder/App_builder.php';
include_once '../mkdcore/builder/Preschema_builder.php';

echo '<hr/>';
echo '<h1>SAAS Deployment</h1>';
echo '<a href="/generator.php?action=start">Start Build</a><br/>';
echo '<a href="/generator.php?action=clean">Clean Build</a><br/>';
echo '<a href="/generator.php?action=rebuild">ReBuild</a><br/>';
echo '<a href="/generator.php?action=random">Random Key</a><br/>';
echo '<a href="/generator.php?action=crud">Crud Builder</a><br/>';
echo '<a href="/generator.php?action=preschema">Preschema</a><br/>';

$action = !empty($_GET['action']) ? $_GET['action'] : '';

$raw_configuration = file_get_contents('configuration.json');

$config = json_decode($raw_configuration, TRUE);

if (file_exists('env.json')) {
    $env_configuration = file_get_contents('env.json');
    $env = json_decode($env_configuration, TRUE);
    $config['config'] = $env['config'];
    $config['database'] = $env['database'];
    $raw_configuration = json_encode($config);
}
if ($action == 'start') {
    $builder = new App_builder($raw_configuration);
    $builder->build();
}

if ($action == 'clean') {
    $builder = new App_builder($raw_configuration);
    $builder->destroy();
}

if ($action == 'preschema') {
    $raw_configuration = file_get_contents('preschema_base.schema');
    $builder = new Preschema_builder($raw_configuration);
    $builder->init();
}

if ($action == 'random') {
    echo '<h1>Random Key</h1>';
    echo '<p>' . sha1(uniqid() . time()). '</p>';
}

if ($action == 'rebuild') {
    $builder = new App_builder($raw_configuration);
    $builder->destroy();
    $builder = new App_builder($raw_configuration);
    $builder->build();
}