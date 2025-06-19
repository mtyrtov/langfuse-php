<?php

declare(strict_types=1);

namespace Langfuse;

use Langfuse\Exceptions\LangfuseException;
use Langfuse\Models\Prompt;

final class PromptManager
{
    public function __construct(
        private readonly LangfuseClient $client,
    ) {}

    /**
     * @throws LangfuseException
     */
    public function fetchPrompt(string $promptName): Prompt
    {
        return $this->client->prompt($promptName);
    }

    /**
     * @throws LangfuseException
     */
    public function getCompiledPrompt(string $promptName, array $variables = []): array
    {
        return $this->fetchPrompt($promptName)->compile($variables);
    }
}
