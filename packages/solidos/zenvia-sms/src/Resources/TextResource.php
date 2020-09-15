<?php

namespace Solidos\ZenviaSms\Resources;

use Solidos\ZenviaSms\Exceptions\FieldMissingException;

class TextResource
{
    private string $text;

    /**
     * TextResource constructor.
     * @param string $text
     * @throws FieldMissingException
     */
    public function __construct(string $text)
    {
        if(blank($text)){
            throw new FieldMissingException('Texto');
        }
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
