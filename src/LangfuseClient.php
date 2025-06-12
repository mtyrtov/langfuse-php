<?php

declare(strict_types=1);

namespace Langfuse;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Langfuse\Exceptions\LangfuseException;
use Langfuse\Models\Prompt;

final class LangfuseClient
{
    private HttpClient $httpClient;

    public function __construct(
        string $publicKey,
        string $secretKey,
        private string $host = 'http://127.0.0.1:3000',
    ) {
        $this->host = \rtrim($host, '/');
        $this->httpClient = new HttpClient([
            'base_uri' => $this->host,
            'auth' => [$publicKey, $secretKey],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @throws LangfuseException
     */
    private function fetch(string $method, string $uri, array $query = [], array $payload = []): array
    {
        try {
            $response = $this->httpClient->request($method, $uri, [
                'query' => $query,
                'json' => $payload,
            ]);

            return \json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            throw new LangfuseException(
                message: $exception->getResponse()->getBody()->getContents(),
                code: $exception->getCode(),
                previous: $exception
            );
        }
    }

    /**
     * @throws LangfuseException
     */
    public function health(): array
    {
        return $this->fetch(method: 'GET', uri: '/api/public/health');
    }

    /**
     * @throws LangfuseException
     */
    public function traces(array $query = []): array
    {
        return $this->fetch('GET', '/api/public/traces', $query);
    }

    /**
     * @throws LangfuseException
     */
    public function createAnnotationQueueItem(
        string $queueId,
        string $objectId,
        string $objectType,
        ?string $status = null
    ): array {
        $payload = [
            'objectId' => $objectId,
            'objectType' => $objectType,
        ];

        if ($status !== null) {
            $payload['status'] = $status;
        }

        return $this->fetch(
            method: 'POST',
            uri: "/api/public/annotation-queues/$queueId/items",
            payload: $payload,
        );
    }

    /**
     * @throws LangfuseException
     */
    public function score(array $data): array
    {
        return $this->fetch(method: 'POST', uri: '/api/public/scores', payload: $data);
    }

    /**
     * @throws LangfuseException
     */
    public function ingestion(array $batch): array
    {
        return $this->fetch(method: 'POST', uri: '/api/public/ingestion', payload: ['batch' => $batch]);
    }

    /**
     * @throws LangfuseException
     */
    public function prompt(string $name, ?int $version = null, ?string $label = null): Prompt
    {
        return Prompt::fromArray($this->fetch(
            method: 'GET',
            uri: "/api/public/v2/prompts/$name",
            query: [
                'version' => $version,
                'label' => $label,
            ],
        ));
    }

    /**
     * @throws LangfuseException
     */
    public function promptsList(
        ?string $name = null,
        ?string $label = null,
        ?string $tag = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $fromUpdatedAt = null,
        ?string $toUpdatedAt = null,
    ): array {
        return $this->fetch(
            method: 'GET',
            uri: '/api/public/v2/prompts',
            query: [
                'name' => $name,
                'label' => $label,
                'tag' => $tag,
                'page' => $page,
                'limit' => $limit,
                'fromUpdatedAt' => $fromUpdatedAt,
                'toUpdatedAt' => $toUpdatedAt,
            ],
        );
    }

    /**
     * @throws LangfuseException
     */
    public function promptCreate(array $prompt): array
    {
        return $this->fetch(
            method: 'POST',
            uri: '/api/public/v2/prompts',
            payload: $prompt,
        );
    }
}
