<?php
namespace AuLait;

use AuLait\Exception\DependencyInjectionException;

class DependencyInjection
{
    protected $factory = [];
    protected $container = [];

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if (!isset($this->factory[$key])) {
            throw new DependencyInjectionException(
                "No register factory $key",
                DependencyInjectionException::CODE_NO_REGISTER_FACTORY
            );
        }

        return call_user_func($this->factory[$key]);
    }

    /**
     * @param string $key
     * @param callable $callback
     * @return $this
     * @throws \Exception
     */
    public function set($key, $callback)
    {
        if (!is_callable($callback)) {
            throw new DependencyInjectionException(
                'Second parameter must be callable.',
                DependencyInjectionException::CODE_INVALID_PARAMETER
            );
        }

        $this->factory[$key] = $callback;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function share($key)
    {
        if (!isset($this->container[$key])) {
            $this->container[$key] = $this->get($key);
        }

        return $this->container[$key];
    }

}
