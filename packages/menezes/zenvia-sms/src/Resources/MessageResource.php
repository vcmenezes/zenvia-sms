<?php

namespace Menezes\ZenviaSms\Resources;

use Exception;
use Menezes\ZenviaSms\Collections\NumberCollection;
use Menezes\ZenviaSms\Exceptions\FieldMissingException;

class MessageResource
{
    private ?TextResource $text;

    private NumberCollection $numbers;

    private FromResource $from;

    private bool $isMultiNumbers;

    private int $aggregateId;

    /**
     * MessageResource constructor.
     * @param FromResource $from
     * @param NumberCollection $numbers
     * @param TextResource|null $text
     * @throws FieldMissingException
     * @throws Exception
     */
    public function __construct(FromResource $from, NumberCollection $numbers, TextResource $text = null)
    {
        if ($numbers->isEmpty()) {
            throw new FieldMissingException('Número');
        }
        $this->text = $text;
        $this->numbers = $numbers;
        $this->from = $from;

        $this->isMultiNumbers = $this->numbers->isMultiNumbers();

        $this->aggregateId = random_int(1, 9999);
    }

    public function isMultiMessage(): bool
    {
        return $this->isMultiNumbers;
    }

    /**
     * @return array
     * @throws FieldMissingException
     */
    private function getBodyMultiNumbers(): array
    {
        $numbersBodys = [];

        /** @var NumberResource $number */
        foreach ($this->numbers->get() as $number) {
            $numbersBodys[] = $number->getBodyRequest($this->from, $this->text);
        }

        return $numbersBodys;
    }

    /**
     * @return array
     * @throws FieldMissingException
     */
    private function getBodyOneNumber(): array
    {
        $number = $this->numbers->first();

        return $number->getBodyRequest($this->from, $this->text, $this->aggregateId);
    }

    /**
     * @return array|array[]
     * @throws FieldMissingException
     */
    public function getBodyRequest(): array
    {
        if ($this->isMultiNumbers) {
            return [
                'sendSmsMultiRequest' => [
                    'aggregateId' => $this->aggregateId,
                    'sendSmsRequestList' => $this->getBodyMultiNumbers()
                ]
            ];
        }
        return [
            'sendSmsRequest' => $this->getBodyOneNumber()
        ];
    }
}
