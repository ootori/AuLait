<?php
namespace AuLait;
class Request
{
    /** @var DependencyInjection */
    public $di = null;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @param mixed $filter
     * @return null
     */
    public function getQuery($key, $default = null, $filter = null)
    {
        $value = $default;
        if (isset($_GET[$key])) {
            $value = $_GET[$key];
        }
        if ($filter !== null) {
            // TODO: filter処理
        }
        return $value;
    }

    public function getQueries()
    {
        return $_GET;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @param mixed $filter
     * @return null
     */
    public function getRequest($key, $default = null, $filter = null)
    {
        $value = $default;
        if (isset($_POST[$key])) {
            $value = $_POST[$key];
        }
        if ($filter !== null) {
            // TODO: filter処理
        }
        return $value;
    }

    public function getRequests()
    {
        return $_POST;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getFile($key = null)
    {
        return $_FILES[$key] ?? [];
    }
}
