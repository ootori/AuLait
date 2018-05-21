<?php
namespace AuLait;

/**
 * Class AutoLoader
 * @package AuLait
 */
class AutoLoader
{
    static protected $modules = [];

    static public function addModule($module)
    {
        self::$modules[] = $module;
    }

    static public function register()
    {
        spl_autoload_register('self::load', true, true);
    }

    static public function load($class)
    {
        $class = str_replace('\\', '/', $class);
        $path = SYSTEM_ROOT . '/app/' . $class . '.php';
        if (file_exists($path)) {
            require_once($path);
            return;
        }
    }
}
