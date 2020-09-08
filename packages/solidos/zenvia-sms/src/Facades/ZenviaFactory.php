<?php

namespace Solidos\ZenviaSms\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class ZenviaFactory extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'zenvia';
    }
}
