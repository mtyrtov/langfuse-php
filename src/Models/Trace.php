<?php

declare(strict_types=1);

namespace Langfuse\Models;

use Langfuse\Enum\EventTypeEnum;
use Langfuse\LangfuseProfiler;
use Langfuse\Models\Observations\Event;
use Langfuse\Models\Observations\Generation;
use Langfuse\Models\Observations\Span;
use Langfuse\Traits\DataTransferTrait;

final class Trace extends AbstractEntity
{
    use DataTransferTrait;

    public EventTypeEnum $type = EventTypeEnum::TRACE;
    private ?string $sessionId = null;
    private ?string $userId = null;
    private ?array $tags = null;
    private ?array $meta = null;

    public function __construct(
        public string $id,
        public string $name,
        private readonly LangfuseProfiler $profiler,
    ) {
        parent::__construct($id, $name);
    }

    public function span(string $name): Span
    {
        $profilerObject = $this->profiler->getObjects();
        $eventKey = "{$name}." . EventTypeEnum::SPAN->value;

        if (\array_key_exists($eventKey, $profilerObject)) {
            return $profilerObject[$eventKey];
        }

        $span = new Span(
            id: $this->generateId(),
            name: $name,
            traceId: $this->id,
            parent: $this,
        );

        $this->profiler->register($span);
        return $span;
    }

    public function generation(string $name): Generation
    {
        $generation = new Generation(
            id: $this->generateId(),
            name: $name,
            traceId: $this->id,
            parent: $this,
        );

        $this->profiler->register($generation);
        return $generation;
    }

    public function event(string $name): Event
    {
        $event = new Event(
            id: $this->generateId(),
            name: $name,
            traceId: $this->id,
            parent: $this,
        );

        $this->profiler->register($event);
        return $event;
    }

    public function setSessionId(?string $sessionId = null): self
    {
        $this->sessionId = $sessionId ?? $this->generateId();
        return $this;
    }

    public function setMeta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    # TODO: полный бред
    public function getProfiler(): LangfuseProfiler
    {
        return $this->profiler;
    }

    protected function buildBody(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'input' => $this->input,
            'output' => $this->output,
            'sessionId' => $this->sessionId,
            'userId' => $this->userId,
            'tags' => $this->tags,
            'metadata' => empty($this->meta) ? null : $this->meta,
            'timestamp' => $this->getTimestamp(),
        ];
    }
}
