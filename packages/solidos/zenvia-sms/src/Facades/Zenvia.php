<?php

namespace Solidos\ZenviaSms\Facades;

use Illuminate\Support\Facades\Facade;
use Solidos\ZenviaSms\Collections\MessageCollection;
use Solidos\ZenviaSms\Resources\NumberResource;

/**
 * Class Zenvia
 * @package Solidos\ZenviaSms\Facades
 * @method static self setNumber(string|string[]|NumberResource|NumberResource[] $numbers)
 * @method static self setText(string $text)
 * @method static MessageCollection getMessage()
 * @method static void send()
 * @method static void sendMessage(string|string[]|NumberResource|NumberResource[] $numbers, string $text)
 * @method static self withoutFrom()
 * @method static self setFrom($from)
 */

class Zenvia extends Facade
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
