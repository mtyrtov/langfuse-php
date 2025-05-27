<?php

declare(strict_types=1);

namespace Langfuse\Models\Observations;

use Carbon\Carbon;
use Langfuse\Enum\EventTypeEnum;
use Langfuse\Models\Prompt;

final class Generation extends AbstractObservation
{
    public EventTypeEnum $type = EventTypeEnum::GENERATION;
    public ?string $prompt = null;
    public ?string $model = null;
    public array $modelParameters = [];
    public ?Carbon $endTime = null;

    public function getEndTime(): ?string
    {
        return $this->endTime?->format('Y-m-d\TH:i:s.u\Z');
    }

    public function setEndTime(?Carbon $endTime = null): self
    {
        $this->endTime = $endTime ?? Carbon::now();
        return $this;
    }

    public function setPrompt(?string $promptName): self
    {
        $this->prompt = $promptName;
        return $this;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function setModelParameters(array $modelParameters): self
    {
        $this->modelParameters = $modelParameters;
        return $this;
    }

    protected function buildBody(): array
    {
        $body = [
            'id' => $this->generateId(),
            'traceId' => $this->traceId,
            'name' => $this->name,
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndtime(),
            'input' => $this->input,
            'output' => $this->output,
            'model' => $this->model,
            'modelParameters' => empty($this->modelParameters) ? null : $this->modelParameters,
            # 'promptName' => $this->prompt,
            # 'promptVersion' => 1, # $this->prompt?->version,
            'tags' => $this->tags,
            'metadata' => empty($this->meta) ? null : $this->meta,
            'timestamp' => $this->getStartTime(),
        ];

        if ($this->prompt) {
            $body['promptName'] = $this->prompt;
            $body['promptVersion'] = 1;
        }

        if ($this->parent) {
            $body['parentObservationId'] = $this->parent->id;
        }

        return $body;
    }
}
