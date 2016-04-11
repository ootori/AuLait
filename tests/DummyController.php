<?php
class DummyController extends AuLait\Controller
{
    public function getAction($param1, $param2, $param3 = 'c') {
        echo "$param1, $param2, $param3";
    }
}
