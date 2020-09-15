<?php

namespace Solidos\ZenviaSms\Exceptions;

use Exception;
use Illuminate\Http\Response;

class FieldMissingException extends Exception
{
    public function __construct($field)
    {
        parent::__construct('Campo ' . $field . ' é requerido', Response::HTTP_BAD_REQUEST);
    }
}
