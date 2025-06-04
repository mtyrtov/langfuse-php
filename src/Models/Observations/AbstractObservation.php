<?php

declare(strict_types=1);

namespace Langfuse\Models\Observations;

use Carbon\Carbon;
use Langfuse\LangfuseProfiler;
use Langfuse\Models\AbstractEntity;
use Langfuse\Models\Trace;

# TODO: какая-то фигня с профайлером тут происходит
abstract class AbstractObservation extends AbstractEntity
{
    protected ?LangfuseProfiler $profiler = null;

    public function __construct(
        string $id,
        public string $name,
        protected string $traceId,
        ?AbstractEntity $parent = null,
        public ?Carbon $startTime = null,
        protected ?array $tags = [],
    ) {
        parent::__construct($id, $this->name);

        if (empty($this->startTime)) {
            $this->startTime = Carbon::now();
        }

        if ($parent) {
            $this->setParent($parent);
            $this->setEnvironment($parent->environment);

            # получаем доступ к profiler через родительский объект
            if ($parent instanceof Trace) {
                $this->profiler = $parent->getProfiler();
            } elseif ($parent instanceof AbstractObservation) {
                $this->profiler = $parent->profiler;
            }
        }
    }

    public function getStartTime(): ?string
    {
        return $this->startTime?->format('Y-m-d\TH:i:s.u\Z');
    }

    public function setStartTime(Carbon $startTime): static
    {
        $this->startTime = $startTime;
        return $this;
    }
}
