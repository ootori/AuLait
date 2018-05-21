<?php
namespace AuLait\Test\View;

use AuLait\DependencyInjection;
use AuLait\Security;
use AuLait\Router;
use AuLait\View\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    protected $di = null;

    public function setUp()
    {
        parent::setUp();

        $di = new DependencyInjection();
        $di->set('router', function () {
            $router = new Router();
            $router->add(
                'index',
                '/',
                [
                    'controller' => '\AuLait\Controller'
                ]
            );
            return $router;
        });
        $di->set('security', function () use ($di) {
            $security = new Security($di);
            return $security;
        });
        $this->di = $di;
    }

    public function testSanitize()
    {
        $data = '<span>test</span>';
        $expected =  '&lt;span&gt;test&lt;/span&gt;';

        $helper = new Helper($this->di);
        $result = $helper->sanitize($data);

        $this->assertEquals($expected, $result);
    }

    static public function providerRoute()
    {
        return [
            [
                '/',
                [],
                true
            ],
            [
                '/?param1=abc&amp;param2=xyz',
                [
                    'param1' => 'abc',
                    'param2' => 'xyz'
                ],
                true
            ],
            [
                '/?param1=abc&param2=xyz',
                [
                    'param1' => 'abc',
                    'param2' => 'xyz'
                ],
                false
            ]
        ];
    }

    /**
     * @dataProvider providerRoute
     */
    public function testRoute($expected, $param, $sanitize)
    {
        $name = 'index';

        $helper = new Helper($this->di);
        $result = $helper->route($name, $param, $sanitize);

        $this->assertEquals($expected, $result);
    }

    public function testError()
    {
        $data = ['"エラー"です。'];
        $expected = '<span class="error-messsage">&quot;エラー&quot;です。</span>';
        $helper = new Helper($this->di);
        $result = $helper->error($data);

        $this->assertEquals($expected, $result);
    }

    public function testMethod()
    {
        $helper = new Helper($this->di);
    }

}
