<?php

namespace Menezes\ZenviaSms\Resources;

use Menezes\ZenviaSms\Exceptions\FieldMissingException;

class FromResource
{
    private string $from;

    /**
     * FromResource constructor.
     * @param string $from
     * @throws FieldMissingException
     */
    public function __construct(string $from)
    {
        if (blank($from)) {
            throw new FieldMissingException('Remetente');
        }
        $this->from = $from;
    }

    public function getFrom(): string
    {
        return $this->from;
    }
}
