<?php

declare(strict_types=1);

namespace Langfuse;

final class ScoreManager
{
    public function __construct(
        private readonly LangfuseClient $client,
    ) {}

    public function createScore(string $name, float|string $value, array $options = []): string
    {
        $data = array_merge($options, [
            'name' => $name,
            'value' => $value,
        ]);

        $response = $this->client->score($data);

        return $response['id'];
    }

    public function createTraceScore(string $traceId, string $name, float|string $value, array $options = []): string
    {
        return $this->createScore($name, $value, array_merge($options, ['traceId' => $traceId]));
    }

    public function createSessionScore(string $sessionId, string $name, float|string $value, array $options = []): string
    {
        return $this->createScore($name, $value, array_merge($options, ['sessionId' => $sessionId]));
    }

    public function createObservationScore(string $observationId, string $name, float|string $value, array $options = []): string
    {
        return $this->createScore($name, $value, array_merge($options, ['observationId' => $observationId]));
    }

    public function createNumericScore(string $name, float $value, array $options = []): string
    {
        return $this->createScore($name, $value, array_merge($options, ['dataType' => 'NUMERIC']));
    }

    public function createBooleanScore(string $name, bool $value, array $options = []): string
    {
        return $this->createScore($name, $value ? 1 : 0, array_merge($options, ['dataType' => 'BOOLEAN']));
    }

    public function createCategoricalScore(string $name, string $value, array $options = []): string
    {
        return $this->createScore($name, $value, array_merge($options, ['dataType' => 'CATEGORICAL']));
    }
}
