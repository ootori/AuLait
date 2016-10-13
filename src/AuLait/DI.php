<?php
namespace AuLait;
class DI
{
    static protected $container = array();

    /**
     * @param DependencyInjection $di
     */
    static public function setDefault(DependencyInjection $di) {
        self::$container['_default'] = $di;
    }

    /**
     * @return DependencyInjection
     */
    static public function getDefault() {
        return self::$container['_default'];
    }

}
