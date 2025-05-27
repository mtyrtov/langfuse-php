<?php

declare(strict_types=1);

namespace Langfuse;

use Langfuse\Exceptions\LangfuseException;
use Langfuse\Models\AbstractEntity;
use Langfuse\Models\Observations\AbstractObservation;
use Langfuse\Models\Trace;
use Ramsey\Uuid\Uuid;

# TODO: span context & open ai wrapper
# 1. [ x ] span context
# 2. [ ] open ai wrapper
# 3. [ ] wrapper: integrated and/or callback
# 4. [ ] groq: wrapper custom client and/or callback
final class LangfuseProfiler
{
    private ?Trace $trace = null;
    private array $objects = [];

    public function __construct(
        private readonly LangfuseClient $client,
        private readonly bool $enabled = true,
    ) {}

    public function getObjects(): array
    {
        return $this->objects;
    }

    public function getTrace(): ?Trace
    {
        if (empty($this->trace)) {
            return null;
        }

        return $this->trace;
    }

    public function trace(string $name): Trace
    {
        $id = Uuid::uuid4()->toString();
        $this->trace = new Trace($id, $name, $this);

        $this->register($this->trace); # TODO: reset() ?

        return $this->trace;
    }

    public function register(AbstractEntity $entity): void
    {
        $this->objects["{$entity->name}." . $entity->type->value] = $entity;
    }

    /**
     * @throws LangfuseException
     */
    public function flush(bool $normalize = true): array
    {
        if (empty($this->enabled)) {
            return ['status' => 'success', 'message' => 'Profiler is disabled'];
        }

        if (empty($this->objects)) {
            return ['status' => 'success', 'message' => 'No data to flush'];
        }

        if ($normalize) {
            $this->normalizeObservationTimes();
        }

        $batch = \array_map(
            fn (AbstractEntity $entity) => $entity->toArray(),
            $this->objects,
        );

        $result = $this->client->ingestion(\array_values($batch));
        $this->objects = [];

        return $result;
    }

    /**
     * Normalizes the start times of observations to ensure proper ordering
     * by adding milliseconds to observations with identical timestamps.
     */
    private function normalizeObservationTimes(): void
    {
        $observations = [];

        # First, collect all AbstractObservation objects
        foreach ($this->objects as $entity) {
            if ($entity instanceof AbstractObservation) {
                $observations[] = $entity;
            }
        }

        # Sort observations by startTime
        \usort($observations, function (AbstractObservation $a, AbstractObservation $b) {
            return $a->startTime <=> $b->startTime;
        });

        # Group observations by millisecond timestamp
        $groupedByMs = [];
        foreach ($observations as $observation) {
            $key = $observation->startTime->format('Y-m-d\TH:i:s.v'); # Format with milliseconds only
            $groupedByMs[$key][] = $observation;
        }

        # For each group with multiple observations, increment milliseconds sequentially
        foreach ($groupedByMs as $group) {
            if (\count($group) > 1) {
                # Skip the first one, keep its original timestamp
                for ($i = 1; $i < \count($group); $i++) {
                    # Add i milliseconds to ensure unique timestamps
                    $group[$i]->startTime->addMilliseconds($i);
                }
            }
        }
    }
}
