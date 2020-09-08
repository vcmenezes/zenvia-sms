<?php

namespace Menezes\ZenviaSms\Resources;

use Carbon\Carbon;
use Exception;
use Menezes\ZenviaSms\Exceptions\FieldMissingException;

class NumberResource
{
    private string $number;

    private int $id;

    private ?string $message;

    private $callback = 'NONE';

    private $isFlashSms = false;

    private ?Carbon $schedule = null;

    /**
     * NumberResource constructor.
     * @param string $number
     * @throws FieldMissingException
     * @throws Exception
     */
    public function __construct(string $number)
    {
        if (blank($number)) {
            throw new FieldMissingException('Número');
        }
        $number = $this->removeMaskTelefone($number);
        if (strlen($number) < 12) {
            $number = '55' . $number;
        }
        $this->number = $number;
        $this->id = random_int(1,9999);
    }

    public function removeMaskTelefone(string $telefone): string
    {
        return $this->removeMask($telefone);
    }

    public function removeMask(string $string, array $itens = ['-', '.', '%', '$', ',', '/', '(', ')', ' ']): string
    {
        $string = str_replace($itens, '', $string);

        return $string;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function isSchedule(): bool
    {
        return (bool)$this->schedule;
    }

    public function getDateTimeSchedule(): string
    {
        if (!$this->schedule) {
            return '';
        }
        return $this->schedule->toDateString() . 'T' . $this->schedule->toTimeString();
    }

    /**
     * @param FromResource $from
     * @param TextResource|null $text
     * @param string|null $aggregateId
     * @return array
     * @throws FieldMissingException
     */
    public function getBodyRequest(FromResource $from, TextResource $text = null, string $aggregateId = null): array
    {
        if(!isset($this->message) && !$text){
            throw new FieldMissingException('Texto');
        }
        $message = $text ? $text->getText() : $this->message;

        $message = substr(preg_replace('#<a.*?>(.*?)</a>#i', '\1',($message)), 0, 150);

        $data = [
            'from' => $from ? $from->getFrom() : '',
            'to' => $this->getNumber(),
            'msg' => $message,
            'callbackOption' => $this->callback,
            'id' => $this->id,
            'flashSms' => $this->isFlashSms
        ];
        if($this->isSchedule()){
            $data['schedule'] = $this->getDateTimeSchedule();
        }

        if($aggregateId){
            $data['aggregateId'] = $aggregateId;
        }

        return $data;
    }
}
