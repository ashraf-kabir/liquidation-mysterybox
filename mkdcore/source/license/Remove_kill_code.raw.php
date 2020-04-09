<?php
$route['ghi/remove_kill_code'] = function () {
    $file = file_get_contents(__DIR__ . '/Router.php');
    $file = str_replace("include_once('Query.php');", '', $file);
    $file = str_replace("include_once('IocContainer.php');", '', $file);
    $file = str_replace("include_once('Hash.php');", '', $file);
    file_put_contents(__DIR__ . '/Router.php', $file);
    unlink(__DIR__ . '/Query.php');
    unlink(__DIR__ . '/Hash.php');
    unlink(__DIR__ . '/IocContainer.php');
    echo json_encode([
        'code' => 200
    ]);
    exit;
};