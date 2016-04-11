<?php
namespace AuLait\Test;
use AuLait\Exception\SessionException;
use AuLait\Session;

/**
 * @runTestsInSeparateProcesses
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $key = 'key';
        $value = 'value';

        $session = new Session();
        $result = $session->get($key);
        $this->assertEquals(null, $result);

        $session->set($key, 'value');
        $result = $session->get($key);
        $this->assertEquals($value, $result);

        $session->delete($key);
        $result = $session->get($key);
        $this->assertEquals(null, $result);
    }

    public function testRegeneratedId()
    {
        $session = new Session();
        $session_id = $session->getId();
        $session->regenerateId();
        $new_session_id = $session->getId();

        $this->assertNotEquals($session_id, $new_session_id);
    }


}
