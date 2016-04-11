<?php
namespace AuLait\Validator;

class Email extends Base
{
    /**
     * @param string $value
     * @return array
     */
    public function validate($value)
    {
        $messages = [];
        if ($value===null || '' === $value) {
            return $messages;
        }

        $value = (string) $value;
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        if (!$valid) {
            $messages[] = '有効なメールアドレスを入力してください。';
        }
        return $messages;
    }
}

