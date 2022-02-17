<?php
namespace AuLait;

class Loader
{
    protected $systemRoot = "";
    protected $appDir = "";

    public function __construct($systemRoot, $appDir = "app")
    {
        $this->systemRoot = $systemRoot;
        $this->appDir = $appDir;
    }

    public function register()
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        $class = str_replace('\\', '/', $class);
        $path = $this->systemRoot . DIRECTORY_SEPARATOR . $this->appDir . DIRECTORY_SEPARATOR . $class . '.php';
        if (file_exists($path)) {
            require_once($path);
            return;
        }
    }
}
