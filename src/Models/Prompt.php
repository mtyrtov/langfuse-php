<?php

declare(strict_types=1);

namespace Langfuse\Models;

use Langfuse\Traits\DataTransferTrait;

# TODO: types
final class Prompt
{
    use DataTransferTrait;

    /** @var list<string> */
    private readonly array $variables;
    private readonly ModelConfig $promptConfig;

    public function __construct(
        /** @var non-empty-string */
        public readonly string $id,
        /** @var non-empty-string */
        public readonly string $name,
        /** @var list<array{role: string, content: string}> */
        public readonly array $prompt,
        /** @var positive-int */
        public readonly int $version,
        /** @var array<string, string|int|float|bool|null> */
        public readonly array $config,
        /** @var list<string> */
        public readonly array $labels,
        /** @var list<string> */
        public readonly array $tags,
    ) {
        $this->variables = $this->extractVariables();
        $this->promptConfig = ModelConfig::fromArray($this->config);
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getModelConfig(): ModelConfig
    {
        return $this->promptConfig;
    }

    public function compile(array $variables): array
    {
        $compiledPrompt = [];

        foreach ($this->prompt as $message) {
            $content = $message['content'];

            foreach ($variables as $key => $value) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
            }

            $compiledPrompt[] = [
                'role' => $message['role'],
                'content' => $content,
            ];
        }

        return $compiledPrompt;
    }

    private function extractVariables(): array
    {
        $variables = [];
        $pattern = '/\{\{([a-zA-Z_][a-zA-Z0-9_]*)\}\}/';

        foreach ($this->prompt as $message) {
            if (isset($message['content'])) {
                preg_match_all($pattern, $message['content'], $matches);
                if (!empty($matches[1])) {
                    $variables = array_merge($variables, $matches[1]);
                }
            }
        }

        # Убираем дубликаты и переиндексируем массив
        return array_values(array_unique($variables));
    }
}
