<?php

namespace Menezes\ZenviaSms\Collections;

use Illuminate\Support\Collection;
use Menezes\ZenviaSms\Resources\MessageResource;

class MessageCollection
{
    private Collection $messages;

    public function __construct($messages = [])
    {
        $this->messages = collect($messages);
    }

    public function add(MessageResource $messageResource): void
    {
        $this->messages[] = $messageResource;
    }

    public function get(): array
    {
        return $this->messages->all();
    }
}
