<?php

declare(strict_types=1);

namespace Langfuse;

use Langfuse\Exceptions\LangfuseException;

final class TraceManager
{
    public function __construct(
        private readonly LangfuseClient $client,
    ) {}

    /**
     * @throws LangfuseException
     */
    public function getLastTraceIdBySession(string $sessionId): ?string
    {
        $response = $this->client->traces([
            'sessionId' => $sessionId,
            'orderBy' => 'timestamp.desc',
            'limit' => 1,
        ]);

        return $response['data'][0]['id'] ?? null;
    }
}
