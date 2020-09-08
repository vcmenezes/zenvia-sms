<?php

namespace Menezes\ZenviaSms\Collections;

use Illuminate\Support\Collection;
use Menezes\ZenviaSms\Exceptions\FieldMissingException;
use Menezes\ZenviaSms\Resources\NumberResource;

class NumberCollection
{
    private Collection $numbers;

    public function __construct($numbers = [])
    {
        $this->numbers = collect($numbers);
    }

    /**
     * @param $number
     * @return $this
     * @throws FieldMissingException
     */
    public function addNumber($number): NumberCollection
    {
        if(!$number instanceof NumberResource){
            $number = new NumberResource($number);
        }
        $this->numbers[] = $number;

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->numbers->isEmpty();
    }

    public function get(): Collection
    {
        return $this->numbers;
    }

    public function isMultiNumbers(): bool
    {
        return $this->numbers->count() > 1;
    }

    public function first(): NumberResource
    {
        return $this->numbers->first();
    }
}
