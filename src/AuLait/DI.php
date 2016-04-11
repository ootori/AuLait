<?php
namespace AuLait;
class DI
{
    static protected $container = array();

    /**
     * @param DependencyInjection $di
     */
    static function setDefault(DependencyInjection $di) {
        self::$container['_default'] = $di;
    }

    /**
     * @return DependencyInjection
     */
    static function getDefault() {
        return self::$container['_default'];
    }

}