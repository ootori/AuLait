<?php
namespace AuLait\Test\Form;

use AuLait\Form\Text;

class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic()
    {
        $name = 'test';
        $value = 'value';
        $text = new Text($name);
        $this->assertEquals(null, $text->getValue());
        $text->setValue($value);
        $this->assertEquals($value, $text->getValue());

    }

}
