<?php

declare(strict_types=1);

namespace Langfuse\Models;

use Langfuse\Traits\DataTransferTrait;

# TODO: types
final class Prompt
{
    use DataTransferTrait;

    # /** @var list<string> */
    # public array $variables;

    public function __construct(
        /** @var non-empty-string */
        public string $id,
        /** @var non-empty-string */
        public string $name, # TODO: object
        /** @var list<array{role: string, content: string}> */
        public array $prompt,
        /** @var positive-int */
        public int $version,
        /** @var array<string, string|int|float|bool|null> */
        public array $config,
        /** @var list<string> */
        public array $labels,
        /** @var list<string> */
        public array $tags,
    ) {
        # $this->variables = $this->extractVariables();
    }
}
