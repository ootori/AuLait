<?php

namespace AuLait;

use AuLait\Exception\DispatcherException;

class Dispatcher
{
    /**
     * @var string
     */
    protected $controllerName = 'default';

    /**
     * @var string
     */
    protected $actionName = 'get';

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    public function dispatch()
    {
        $pass = array();

        $className = $this->controllerName . "Controller";
        $actionName = $this->actionName . "Action";
        $args = $this->params;

        if (!class_exists($className)) {
            throw new DispatcherException("Class not found $className", DispatcherException::CODE_CLASS_NOT_FOUND);
        }
        $controller = new $className();

        $controller->initialize();

        if (!method_exists($controller, $actionName)) {
            throw new DispatcherException("Method not found ${className}->${actionName}", DispatcherException::CODE_METHOD_NOT_FOUND);
        }
        $reflectionMethod = new \ReflectionMethod($controller, $actionName);
        foreach ($reflectionMethod->getParameters() as $parameter) {
            if (isset($args[$parameter->getName()])) {
                $pass[] = $args[$parameter->getName()];
            } else {
                $pass[] = $parameter->getDefaultValue();
            }
        }

        $result = $controller->before();

        if ($result) {
            $reflectionMethod->invokeArgs($controller, $pass);
        }

        $controller->after();

    }
}
