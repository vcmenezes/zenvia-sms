<?php

namespace Solidos\ZenviaSms\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AuthenticationNotFoundedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Autenticação não encontrada, verifique as variaveis no .env', Response::HTTP_UNAUTHORIZED);
    }
}
