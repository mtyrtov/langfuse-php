<?php

declare(strict_types=1);

namespace Langfuse\Models\Observations;

use Langfuse\Enum\EventTypeEnum;

final class Event extends AbstractObservation
{
    public EventTypeEnum $type = EventTypeEnum::EVENT;

    protected function buildBody(): array
    {
        $body = [
            'id' => $this->id,
            'traceId' => $this->traceId,
            'name' => $this->name,
            'startTime' => $this->getStartTime(),
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
