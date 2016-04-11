<?php
namespace AuLait\Validator;

class Required extends Base
{
    /**
     * @param string $value
     * @return array
     */
    public function validate($value)
    {
        $messages = [];
        if (!$value){
            $messages[] = '必須入力です';
        }
        return $messages;
    }
}

