<?php
$route['xyz/license'] = function () {
    include_once APPPATH . '/config/setting.php';
    $action = (isset($_GET['action']) && strlen($_GET['action']) > 0) ? $_GET['action'] : '';
    $license_key_set = isset($config) && isset($config['setting']) && isset($config['setting']['license_key']);

    if (!$license_key_set) {
        if ($action == 'stop')
        {
            $file = file_get_contents(__DIR__ . '/../../../index.php');
            $file .= "ob_end_clean();\nob_start();\necho 'INVALID SOFTWARE LICENSE OF SOFTWARE.<br/> Please buy license from <a href=\"https://manaknightdigital.com\">https://manaknightdigital.com</a> to continue using this software.';";
            file_put_contents(__DIR__ . '/../../../index.php', $file);
        }
        echo json_encode(['code' => 404]);
        exit;
    }

    $options = [
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER         => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_ENCODING       => '',
        CURLOPT_AUTOREFERER    => TRUE,
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT        => 120,
    ];

    $domain = $_SERVER['HTTP_HOST'];
    $key = $config['setting']['license_key'];
    $ch = curl_init("http://localhost:9001/license.php?domain=$domain&key=$key");
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);

    json_decode($content);

    if (!(json_last_error() == JSON_ERROR_NONE)) {
        if ($action == 'stop')
        {
            $file = file_get_contents(__DIR__ . '/../../../index.php');
            $file .= "ob_end_clean();\nob_start();\necho 'INVALID SOFTWARE LICENSE OF SOFTWARE.<br/> Please buy license from <a href=\"https://manaknightdigital.com\">https://manaknightdigital.com</a> to continue using this software.';";
            file_put_contents(__DIR__ . '/../../../index.php', $file);
        }
        echo json_encode(['code' => 403]);
        exit;
    }

    $payload = json_decode($content, TRUE);

    if ($payload['code'] != 200) {
        if ($action == 'stop')
        {
            $file = file_get_contents(__DIR__ . '/../../../index.php');
            $file .= "ob_end_clean();\nob_start();\necho 'INVALID SOFTWARE LICENSE OF SOFTWARE.<br/> Please buy license from <a href=\"https://manaknightdigital.com\">https://manaknightdigital.com</a> to continue using this software.';";
            file_put_contents(__DIR__ . '/../../../index.php', $file);
        }
        echo json_encode(['code' => 406]);
        exit;
    }
    echo $content;
    exit;
};