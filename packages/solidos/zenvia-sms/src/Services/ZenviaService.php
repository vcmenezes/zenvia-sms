<?php

namespace Solidos\ZenviaSms\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Solidos\ZenviaSms\Collections\MessageCollection;
use Solidos\ZenviaSms\Collections\NumberCollection;
use Solidos\ZenviaSms\Exceptions\AuthenticationNotFoundedException;
use Solidos\ZenviaSms\Exceptions\FieldMissingException;
use Solidos\ZenviaSms\Requests\EnviarSmsRequest;
use Solidos\ZenviaSms\Resources\AuthenticationResource;
use Solidos\ZenviaSms\Resources\FromResource;
use Solidos\ZenviaSms\Resources\MessageResource;
use Solidos\ZenviaSms\Resources\NumberResource;
use Solidos\ZenviaSms\Resources\TextResource;
use Solidos\ZenviaSms\Responses\ZenviaResponse;
use Throwable;

class ZenviaService
{
    private AuthenticationResource $authentication;

    private FromResource $from;

    private ?NumberCollection $numbers;

    private TextResource $text;

    private MessageCollection $messages;

    /**
     * ZenviaService constructor.
     * @param $account
     * @param $password
     * @throws AuthenticationNotFoundedException|FieldMissingException
     */
    public function __construct($account, $password)
    {
        $this->authentication = new AuthenticationResource($account, $password);

        $this->from = new FromResource(config('zenvia.from', env('ZENVIA_FROM')));
    }

    /**
     * @param $numbers
     * @return $this
     * @throws FieldMissingException
     */
    public function setNumber($numbers): ZenviaService
    {
        if (!isset($this->numbers)) {
            $this->numbers = new NumberCollection();
        }

        if (!is_array($numbers) && !$numbers instanceof Collection) {
            $numbers = (array)$numbers;
        }

        foreach ($numbers as $number) {
            try {
                $this->numbers->addNumber($number instanceof NumberResource ? $number : new NumberResource($number));
            } catch (FieldMissingException $exception) {
                throw $exception;
            } catch (Exception $exception) {
                throw $exception;
            }
        }

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     * @throws FieldMissingException
     */
    public function setText(string $text): ZenviaService
    {
        $this->text = new TextResource($text);

        return $this;
    }

    /**
     * @return MessageCollection
     * @throws FieldMissingException
     */
    public function getMessage(): MessageCollection
    {
        if (!$this->text) {
            throw new FieldMissingException('Texto');
        }
        if ($this->numbers->isEmpty()) {
            throw new FieldMissingException('Número');
        }

        $this->messages = new MessageCollection();

        foreach ($this->numbers->get()->chunk(100) as $numbersChunked) {

            $this->messages->add(new MessageResource($this->from, new NumberCollection($numbersChunked), $this->text));
        }

        return $this->messages;
    }

    /**
     * @throws AuthenticationNotFoundedException
     */
    public function send(): void
    {
        $enviarSmsRequest = new EnviarSmsRequest($this->authentication->getKey());
        $responses = [];

        try {
            foreach ($this->getMessage()->get() as $message) {
                $response = $enviarSmsRequest->send($message);
                if ($response instanceof Collection) {
                    $response = $response->toArray();
                }
                $responses = [...$responses, ...$response];
            }
            Log::info('Mensagens enviadas com sucesso');
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        /** @var ZenviaResponse $response */
        foreach ($responses as $response) {
            if ($response instanceof Collection) {
                foreach ($response as $item) {
                    if ($response->failed()) {
                        Log::error('Error: ' . $response->getDetailCode());
                    }
                }
                continue;
            }

            if ($response->failed()) {
                Log::error('Error: ' . $response->getDetailCode());
            }
        }
    }
}
