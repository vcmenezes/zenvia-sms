<?php

namespace Solidos\ZenviaSms\Collections;

use Illuminate\Support\Collection;
use Solidos\ZenviaSms\Exceptions\FieldMissingException;
use Solidos\ZenviaSms\Resources\NumberResource;

class NumberCollection
{
    /** @var NumberResource[]|Collection $numbers */
    private $numbers;

    public function __construct($numbers = [])
    {
        $this->numbers = collect($numbers);
    }

    public function setNumbers(Collection $numbers): NumberCollection
    {
        $this->numbers = $numbers;
        return $this;
    }

    /**
     * @param $number
     * @return $this
     * @throws FieldMissingException
     */
    public function addNumber(NumberResource $number): NumberCollection
    {
        if(!$number instanceof NumberResource){
            $number = new NumberResource($number);
        }
        $this->numbers[] = $number;

        return $this;
    }

    public function removeNumber(NumberResource $numberToRemove): NumberCollection
    {
        $this->numbers = $this->numbers->reject(static function(NumberResource $number) use ($numberToRemove) {
            return $number->isSameNumber($numberToRemove->getNumber());
        });

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->numbers->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return $this->numbers->isNotEmpty();
    }

    public function count(): int
    {
        return $this->numbers->count();
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
