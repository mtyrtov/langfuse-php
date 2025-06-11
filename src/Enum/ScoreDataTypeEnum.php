<?php

declare(strict_types=1);

namespace Langfuse\Enum;

enum ScoreDataTypeEnum: string
{
    case NUMERIC = 'NUMERIC';
    case BOOLEAN = 'BOOLEAN';
    case CATEGORICAL = 'CATEGORICAL';
}
