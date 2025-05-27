<?php

declare(strict_types=1);

namespace Langfuse\Traits;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\MapperBuilder;

trait DataTransferTrait
{
    public static function fromArray(array $input): self
    {
        try {
            return (new MapperBuilder())
                ->allowSuperfluousKeys()
                ->mapper()
                ->map(self::class, $input);
        } catch (MappingError $error) {
            throw new \InvalidArgumentException($error->getMessage());
        }
    }
}
