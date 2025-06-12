<?php

declare(strict_types=1);

namespace Langfuse\Enum;

enum AnnotationObjectTypeEnum: string
{
    case TRACE = 'TRACE';
    case OBSERVATION = 'OBSERVATION';
}
