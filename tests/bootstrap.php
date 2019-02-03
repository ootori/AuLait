<?php
if (version_compare(PHP_VERSION, '5.6', '<')) {
    ini_set('mbstring.script_encoding', 'UTF-8');
} else {
    ini_set('default_charset', 'UTF-8');
}

spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    $path = '../src/'. $class . '.php';
    if (file_exists($path)) {
        require_once($path);
        return;
    }
});

// DispatcherTest用のDummyControllerクラス
require_once('DummyController.php');


