<?php

declare(strict_types=1);

namespace Langfuse;

use Langfuse\Models\Prompt;

final class PromptManager
{
    public function __construct(
        private readonly LangfuseClient $client,
    ) {}

    /**
     * @throws Exceptions\LangfuseException
     */
    public function getPrompt(string $promptName): Prompt
    {
        return $this->client->prompt($promptName);
    }
}
