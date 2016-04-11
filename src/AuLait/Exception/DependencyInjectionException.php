<?php
namespace AuLait\Exception;
class DependencyInjectionException extends \Exception
{
    const CODE_NO_REGISTER_FACTORY = 1;
    const CODE_INVALID_PARAMETER = 2;
}
