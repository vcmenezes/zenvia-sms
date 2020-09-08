<?php

namespace Menezes\ZenviaSms\Exceptions;

use Exception;
use Throwable;

class RequestException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if($code >= 500){
            $message = 'Erro API Zenvia';
        }
        parent::__construct($message, $code, $previous);
    }
}
