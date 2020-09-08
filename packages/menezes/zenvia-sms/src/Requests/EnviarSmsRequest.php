<?php

namespace Menezes\ZenviaSms\Requests;

use Illuminate\Support\Collection;
use Menezes\ZenviaSms\Exceptions\FieldMissingException;
use Menezes\ZenviaSms\Exceptions\RequestException;
use Menezes\ZenviaSms\Resources\MessageResource;

class EnviarSmsRequest extends BaseRequest
{
    public const URL = '/send-sms';
    public const URLMULTI = '/send-sms-multiple';

    /**
     * @param MessageResource $message
     * @return Collection|null
     * @throws RequestException|FieldMissingException
     */
    public function send(MessageResource $message): ?Collection
    {
        return $this->post($this->getEndpoint($message), $message->getBodyRequest());
    }

    public function getEndpoint(MessageResource $messageResource): string
    {
        return $messageResource->isMultiMessage() ? self::URLMULTI : self::URL;
    }
}
