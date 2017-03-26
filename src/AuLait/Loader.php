<?php
namespace AuLait;
class Loader
{
    protected $modules = [];

    public function addModule($module)
    {
        $this->modules[] = $module;
    }

    public function register()
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
