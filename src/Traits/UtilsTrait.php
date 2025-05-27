<?php

declare(strict_types=1);

namespace Langfuse\Traits;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

trait UtilsTrait
{
    public function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function getTimestamp(): string
    {
        return Carbon::now()->format('Y-m-d\TH:i:s.u\Z');
    }
}
