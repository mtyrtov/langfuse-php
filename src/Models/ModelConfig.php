<?php

declare(strict_types=1);

namespace Langfuse\Models;

final class ModelConfig
{
    public function __construct(
        public readonly string $model = 'gpt-4.1-mini',
        public readonly string $provider = 'openai',
        public readonly float $temperature = 0.0,
        public readonly float $top_p = 1.0,
    ) {}

    public static function fromArray(array $data): ModelConfig
    {
        return new ModelConfig(
            model: (string) ($data['model'] ?? 'gpt-4.1-mini'),
            provider: (string) ($data['provider'] ?? 'openai'),
            temperature: (float) ($data['temperature'] ?? 0.0),
            top_p: (float) ($data['top_p'] ?? 1.0),
        );
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            # 'provider' => $this->provider,
            'temperature' => $this->temperature,
            'top_p' => $this->top_p,
        ];
    }
}
