<?php

namespace Solidos\ZenviaSms\Requests;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Solidos\ZenviaSms\Exceptions\AuthenticationNotFoundedException;
use Solidos\ZenviaSms\Exceptions\RequestException;
use Solidos\ZenviaSms\Responses\ZenviaResponse;
use Throwable;

class BaseRequest
{
    public const ENDPOINT = 'https://api-rest.zenvia.com/services';

    private string $key;

    /**
     * BaseRequest constructor.
     * @param string $key
     * @throws AuthenticationNotFoundedException
     */
    public function __construct(string $key)
    {
        if (blank($key)) {
            throw new AuthenticationNotFoundedException();
        }
        $this->key = $key;
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . $this->key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    private function getOptions($body): array
    {
        return [
            'headers' => $this->getHeaders(),
            'body' => \GuzzleHttp\json_encode($body)
        ];
    }

    private function makeResponse($response): Collection
    {
        $responses = $response['sendSmsMultiResponse']['sendSmsResponseList'] ?? [$response['sendSmsResponse']];
        $responseCollection = collect();
        foreach ($responses as $responseItem) {
            $responseCollection[] = new ZenviaResponse($responseItem);
        }
        return $responseCollection;
    }

    /**
     * @param $url
     * @param $body
     * @return Collection
     * @throws RequestException
     */
    public function post($url, $body): ?Collection
    {
        try {
            $curl = new Client(['verify' => false]);
            $res = $curl->request('POST', self::ENDPOINT . $url, $this->getOptions($body));

            return $this->makeResponse(\GuzzleHttp\json_decode($res->getBody(), true));
        } catch (Throwable $e) {
            throw new RequestException($e->getMessage(), $e->getCode());
        }
    }
}
