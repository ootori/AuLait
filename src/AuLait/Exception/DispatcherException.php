<?php
namespace AuLait\Exception;
class DispatcherException extends \Exception
{
    const CODE_CLASS_NOT_FOUND = 1;
    const CODE_METHOD_NOT_FOUND = 2;
}
