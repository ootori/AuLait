<?php
namespace AuLait\Test;

use AuLait\DependencyInjection;
use AuLait\DI;
use AuLait\Form;
use AuLait\Form\Text;
use AuLait\Request;
use AuLait\Security;
use AuLait\Session;
use AuLait\Validator\Required;

/**
 * @runTestsInSeparateProcesses
 */
class FormTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $di = new DependencyInjection();
        $di->set(
            'security',
            function () use ($di) {
                return new Security($di);
            }
        );
        $di->set(
            'session',
            function () use ($di) {
                return new Session();
            }
        );
        $di->set(
            'request',
            function () use ($di) {
                return new Request($di);
            }
        );
        DI::setDefault($di);
    }

    public function testGet()
    {
        $name = 'name';
        $di = DI::getDefault();
        $form = new Form($di);
        $text = new Text(
            $name
        );
        $text->addValidator(
            new Required()
        );
        $beforeHash = spl_object_hash($text);
        $form->add($text);
        $afterHash = spl_object_hash($form->get($name));
        $this->assertEquals($beforeHash, $afterHash);
    }

    public function testGetValue()
    {
        $name = 'name';
        $value = 'abc';

        $di = DI::getDefault();
        $form = new Form($di);

        $text = new Text(
            $name
        );
        $text->addValidator(
            new Required()
        );
        $text->setValue($value);
        $form->add($text);

        $this->assertEquals($value, $form->getValue($name));
    }


    static public function providerValidateGet()
    {
        return [
            [
                'name',
                'value',
                true,
                []
            ],
            [
                'name',
                null,
                false,
                ['必須入力です']
            ]
        ];
    }

    /**
     * validate(GET時のテスト)
     *
     * @dataProvider providerValidateGet
     * @param $name
     * @param $value
     * @param $expected
     * @param $expectMessages
     */
    public function testValidateGet($name, $value, $expected, $expectMessages)
    {
        $_SERVER['REQUEST_METHOD'] = "GET";

        $di = DI::getDefault();
        $form = new Form($di);

        $name = new Text(
            $name
        );
        $name->addValidator(
            new Required()
        );
        $name->setValue($value);
        $form->add($name);

        $result = $form->validate();
        $this->assertEquals($expected, $result);

        $messages = $form->getErrors();
        $this->assertEquals($expectMessages, $messages);
    }

    /**
     * validate(POST時のテスト)。成功パターン
     * csrfトークンのチェックが強制的に入る
     */
    public function testValidatePostSuccess()
    {
        $name = 'name';
        $value = 'abc';
        $expected = true;
        $csrfToken = DI::getDefault()->share('security')->getCsrfToken('');
        $expectMessages = [];

        $_SERVER['REQUEST_METHOD'] = "POST";
        $_POST['csrf'] = $csrfToken;

        $di = DI::getDefault();
        $form = new Form($di);
        $name = new Text(
            $name
        );
        $name->addValidator(
            new Required()
        );
        $name->setValue($value);
        $form->add($name);

        $csrf = new Form\Hidden(
            'csrf'
        );
        $csrf->addValidator(
            new Required()
        );
        $csrf->setValue($csrfToken);
        $form->add($csrf);

        $result = $form->validate();
        $this->assertEquals($expected, $result);

        $messages = $form->getErrors();
        $this->assertEquals($expectMessages, $messages);
    }

    /**
     * validate(POST時のテスト)。失敗パターン
     * csrfトークンのチェックが強制的に入る
     */
    public function testValidatePostFailure()
    {
        $name = 'name';
        $value = 'abc';
        $expected = false;
        $expectMessages = ['無効なフォームからの送信です。'];
        $expectAllMessages = ['無効なフォームからの送信です。'];

        $_SERVER['REQUEST_METHOD'] = "POST";
        $_POST['csrf'] = '';

        $di = DI::getDefault();
        $form = new Form($di);

        $name = new Text(
            $name
        );
        $name->addValidator(
            new Required()
        );
        $name->setValue($value);
        $form->add($name);

        $result = $form->validate();
        $this->assertEquals($expected, $result);

        $messages = $form->getErrors('csrf');
        $this->assertEquals($expectMessages, $messages);

        $allMessages = $form->getErrors();
        $this->assertEquals($expectAllMessages, $allMessages);
    }

    public function testRender()
    {
        $name = 'test';
        $di = DI::getDefault();
        $form = new Form($di);
        $text = new Text(
            $name
        );
        $form->add($text);

        ob_start();
        $form->render($name);
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<input type="text" name="test" value=""/>', $result);
    }

    public function testGetCsrfToken()
    {
        $di = DI::getDefault();
        $form = new Form($di);
        $token = $form->getCsrfToken();
        $this->assertNotEmpty($token);
    }

    static public function providerIsSent()
    {
        return [
            [
                'back',
                [
                    'back' => '戻る'
                ],
                true,
            ],
            [
                'forward',
                [
                    'back' => '戻る'
                ],
                false,
            ],
            [
                'back',
                [],
                false,
            ],
        ];
    }

    /**
     * isSent(GET時のテスト)
     *
     * @dataProvider providerIsSent
     * @param $name
     * @param $request
     * @param $expected
     */
    public function testIsSentWhenGet($name, $request, $expected)
    {
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_GET = $request;

        $di = DI::getDefault();
        $form = new Form($di);
        $this->assertSame($expected, $form->isSent($name));
    }


    /**
     * isSent(POST時のテスト)
     *
     * @dataProvider providerIsSent
     * @param $name
     * @param $request
     * @param $expected
     */
    public function testIsSentWhenPost($name, $request, $expected)
    {
        $_SERVER['REQUEST_METHOD'] = "POST";
        $_POST = $request;

        $di = DI::getDefault();
        $form = new Form($di);
        $this->assertSame($expected, $form->isSent($name));
    }
}
