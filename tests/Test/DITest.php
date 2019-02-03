<?php
namespace AuLait\Test;

use AuLait\DI;
use AuLait\DependencyInjection;

class DITest extends \PHPUnit\Framework\TestCase
{
    public function testDI()
    {
        $di = new DependencyInjection();
        $hash = spl_object_hash($di);
        DI::setDefault($di);
        $afterDI = DI::getDefault();;
        $getHash = spl_object_hash($afterDI);

        $this->assertEquals($hash, $getHash);
    }
}
