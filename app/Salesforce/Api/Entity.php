<?php

namespace App\Salesforce\Api;

use Illuminate\Support\Arr;

abstract class Entity
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getId(): string
    {
        return $this->getAttribute('Id');
    }

    public function toJson(): string
    {
        return json_encode($this->payload);
    }

    public function toArray(): array
    {
        return $this->payload;
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload);
    }

    protected function getAttribute(string $key): ?string
    {
        return Arr::get($this->payload, $key);
    }
}