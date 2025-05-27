<?php

declare(strict_types=1);

namespace Langfuse\Models\Observations;

use Carbon\Carbon;
use Langfuse\Enum\EventTypeEnum;

final class Span extends AbstractObservation
{
    public EventTypeEnum $type = EventTypeEnum::SPAN;
    public ?Carbon $endTime = null;

    public function end(): self
    {
        return $this->setEndTime(Carbon::now());
    }

    public function event(string $name): Event
    {
        $event = new Event(
            id: $this->generateId(),
            name: $name,
            traceId: $this->traceId,
            parent: $this,
        );

        $this->profiler->register($event);
        return $event;
    }

    public function span(string $name): Span
    {
        $span = new Span(
            id: $this->generateId(),
            name: $name,
            traceId: $this->traceId,
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
            traceId: $this->traceId,
            parent: $this,
        );

        $this->profiler->register($generation);
        return $generation;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime?->format('Y-m-d\TH:i:s.u\Z');
    }

    public function setEndTime(Carbon $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    protected function buildBody(): array
    {
        $body = [
            'id' => $this->id,
            'traceId' => $this->traceId,
            'name' => $this->name,
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndtime(),
            'input' => $this->input,
            'output' => $this->output,
            'tags' => $this->tags,
            'metadata' => empty($this->meta) ? null : $this->meta,
            'timestamp' => $this->getStartTime(),
        ];

        if ($this->parent) {
            $body['parentObservationId'] = $this->parent->id;
        }

        return $body;
    }
}
