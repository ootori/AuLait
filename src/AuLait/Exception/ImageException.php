<?php
namespace AuLait\Exception;
class ImageException extends \Exception
{
    const CODE_FILED_TO_READ_FILE = 1;
    const CODE_UNSUPPORTED_FORMAT = 2;
    const CODE_FILENAME_IS_EMPTY = 3;
}
