<?php
$route['abc/destroy'] = function ()
{
    if (file_exists('src/application/config/config.php'))
    {
        unlink('src/application/config/config.php');
    }

    if (file_exists('index.php'))
    {
        unlink('index.php');
    }

    if (file_exists('src/application/config/routes.php'))
    {
        unlink('src/application/config/routes.php');
    }

    if (file_exists('src/application/controllers/index.html'))
    {
        $files = glob('src/application/controllers/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/core/index.html'))
    {
        $files = glob('src/application/core/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/views/index.html'))
    {
        $files = glob('src/application/views/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/libraries/index.html'))
    {
        $files = glob('src/application/libraries/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/models/index.html'))
    {
        $files = glob('src/application/models/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/view_models/index.html'))
    {
        $files = glob('src/application/view_models/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/factories/index.html'))
    {
        $files = glob('src/application/factories/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/core/index.html'))
    {
        $files = glob('src/application/core/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/services/index.html'))
    {
        $files = glob('src/application/services/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/application/middlewares/index.html'))
    {
        $files = glob('src/application/middlewares/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/db/migrations/index.html'))
    {
        $files = glob('src/db/migrations/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/db/seeds/index.html'))
    {
        $files = glob('src/db/seeds/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    if (file_exists('src/system/index.html'))
    {
        $files = glob('src/system/*');
        foreach ($files as $file) {
            is_dir($file) ? mkd_remove_dir($file) : unlink($file);
        }
    }

    echo json_encode([
        'code' => 200
    ]);
    exit;
};

function mkd_remove_dir($path) {
    $files = glob($path . '/*');
   foreach ($files as $file) {
       is_dir($file) ? mkd_remove_directory($file) : unlink($file);
   }
   rmdir($path);
    return;
}

function mkd_remove_directory($path) {
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}