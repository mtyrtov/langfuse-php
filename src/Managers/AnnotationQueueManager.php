<?php

declare(strict_types=1);

namespace Langfuse\Managers;

use Langfuse\Enum\AnnotationObjectTypeEnum;
use Langfuse\Enum\AnnotationQueueStatusEnum;
use Langfuse\Exceptions\LangfuseException;
use Langfuse\LangfuseClient;

final class AnnotationQueueManager
{
    public function __construct(
        private readonly LangfuseClient $client,
    ) {}

    /**
     * @throws LangfuseException
     */
    public function addItemToQueue(
        string $queueId,
        string $objectId,
        AnnotationObjectTypeEnum $objectType,
        ?AnnotationQueueStatusEnum $status = null
    ): string {
        $response = $this->client->createAnnotationQueueItem(
            $queueId,
            $objectId,
            $objectType->value,
            $status?->value
        );

        return $response['id'];
    }

    /**
     * @throws LangfuseException
     */
    public function addTraceToQueue(
        string $queueId,
        string $traceId,
        ?AnnotationQueueStatusEnum $status = null
    ): string {
        return $this->addItemToQueue(
            $queueId,
            $traceId,
            AnnotationObjectTypeEnum::TRACE,
            $status
        );
    }

    /**
     * @throws LangfuseException
     */
    public function addObservationToQueue(
        string $queueId,
        string $observationId,
        ?AnnotationQueueStatusEnum $status = null
    ): string {
        return $this->addItemToQueue(
            $queueId,
            $observationId,
            AnnotationObjectTypeEnum::OBSERVATION,
            $status
        );
    }
}
