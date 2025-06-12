<?php

declare(strict_types=1);

namespace Langfuse\Enum;

enum AnnotationQueueStatusEnum: string
{
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
}
