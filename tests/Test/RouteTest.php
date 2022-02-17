<?php
namespace AuLait\Test;

use AuLait\Router;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    static public function providerHandle()
    {
        return [
            [
                'index',
                '/',
                [
                    'controller' => 'Index'
                ],
                '/',
                'Index',
                [
                    0 => '/', // TODO: 名前キャプチャ以外のものはreturn時に消すようにしたい。
                ]
            ],
            [
                'equal',
                '/equal',
                [
                    'controller' => 'Equal',
                    'equal' => true,
                ],
                '/equal',
                'Equal',
                []
            ],
            [
                'article_detail',
                '/article/{id}',
                [
                    'controller' => 'Article\Detail'
                ],
                '/article/1000',
                'Article\Detail',
                [
                    0 => '/article/1000',
                    'id' => '1000',
                    1 => '1000',
                ]
            ],
        ];
    }

    /**
     * @dataProvider providerHandle
     * @param $name
     * @param $path
     * @param $option
     * @param $target
     * @param $expected1
     * @param $expected2
     */
    public function testHandle($name, $path, $option, $target, $expected1, $expected2)
    {
        $router = new Router();
        $router->add(
            $name,
            $path,
            $option
        );

        $router->handle($target);
        $this->assertEquals($expected1, $router->getControllerName());
        $this->assertEquals($expected2, $router->getParams());
    }

    /**
     *
     */
    public function testHandle2()
    {
        $route = [
            [
                'tags',
                '/{lang}/tags',
                [
                    'controller' => 'Controllers\Tags'
                ]
            ],
            [
                'admin_tags',
                '/admin/tags',
                [
                    'controller' => 'Controllers\Tags'
                ]
            ]
        ];

        $router = new Router();
        $router->add(
            $name,
            $path,
            $option
        );

        $router->handle($target);
        $this->assertEquals($expected1, $router->getControllerName());
        $this->assertEquals($expected2, $router->getParams());
    }

    public function testGetAction()
    {
        // TODO:あまりよろしくない。
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new Router();
        $this->assertEquals('get', $router->getActionName());
    }

    static public function providerGenerate()
    {
        return [
            [
                'index',
                '/',
                [
                    'controller' => 'Index'
                ],
                [],
                '/'
            ],
            [
                'article_detail',
                '/article/{id}',
                [
                    'controller' => 'Article\Detail'
                ],
                [
                    'action' => 'edit',
                    'id' => 1000
                ],
                '/article/1000?action=edit'
            ],
        ];
    }

    /**
     * @dataProvider providerGenerate
     * @param $name
     * @param $path
     * @param $option
     * @param $parameter
     * @param $expected
     */
    public function testGenerate($name, $path, $option, $parameter, $expected)
    {
        $router = new Router();
//        $router->setDefaultScheme('https');
//        $router->setDefaultServerName('localhost');
        $router->add(
            $name,
            $path,
            $option
        );
        $result = $router->generate($name, $parameter);
        $this->assertEquals($expected, $result);
    }
}
