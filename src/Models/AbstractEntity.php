<?php

declare(strict_types=1);

namespace Langfuse\Models;

use Langfuse\Enum\EventTypeEnum;
use Langfuse\Traits\DataTransferTrait;
use Langfuse\Traits\UtilsTrait;

abstract class AbstractEntity
{
    use DataTransferTrait;
    use UtilsTrait;

    public EventTypeEnum $type;
    protected ?AbstractEntity $parent = null;
    protected null|string|array $input = null;
    protected null|string|array $output = null;
    protected ?string $environment = null;
    protected array $metadata = [];

    public function __construct(
        public string $id,
        public string $name,
    ) {}

    public function setInput(array|string $input): self
    {
        $this->input = $input;
        return $this;
    }

    public function setOutput(null|array|string $output): self
    {
        $this->output = $output;
        return $this;
    }

    public function setParent(AbstractEntity $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function setEnvironment(?string $environment): self
    {
        $this->environment = $environment;
        return $this;
    }

    public function toArray(): array
    {
        $body = $this->buildBody();

        if ($this->environment) {
            $body['environment'] = $this->environment;
        }

        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'body' => $body,
            'timestamp' => $this->getTimestamp(),
        ];
    }

    abstract protected function buildBody(): array;
}
