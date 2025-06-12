# Langfuse PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mtyrtov/langfuse-php.svg?style=flat-square)](https://packagist.org/packages/mtyrtov/langfuse-php)
[![Total Downloads](https://img.shields.io/packagist/dt/mtyrtov/langfuse-php.svg?style=flat-square)](https://packagist.org/packages/mtyrtov/langfuse-php)
[![PHP Version Require](https://img.shields.io/packagist/php-v/mtyrtov/langfuse-php?style=flat-square)](https://packagist.org/packages/mtyrtov/langfuse-php)
[![License](https://img.shields.io/packagist/l/mtyrtov/langfuse-php.svg?style=flat-square)](https://packagist.org/packages/mtyrtov/langfuse-php)

A PHP SDK for interacting with the Langfuse API. This package provides tools for tracing, spans, generations, events and prompt management to enable comprehensive LLM observability.

## Requirements

- PHP >= 8.1

## Installation

```bash
composer require mtyrtov/langfuse-php:dev-master
```

## Quick Start

```php
use Langfuse\LangfuseClient;
use Langfuse\LangfuseProfiler;

require_once "vendor/autoload.php";

# Initialize the client
$client = new LangfuseClient(
    'your-public-key',
    'your-secret-key',
    'https://your-langfuse-host.com' # Optional, defaults to http://127.0.0.1:3000
);

$profiler = new LangfuseProfiler($client);
```

## Core Features

### Tracing

Traces represent full user sessions or requests in your application.

```php
# Create a trace
$trace = $profiler->trace('user-query')
    ->setSessionId() // Automatically generates a session ID
    ->setUserId('user-123');

# Set input and output
$trace->setInput('Hello, how are you?');
$trace->setOutput("I'm fine, thank you!");
```

### Spans

Spans represent logical sections within a trace, such as specific processing steps.

```php
# Create a span within a trace
$classificationSpan = $trace->span('classification');

# End the span when the operation is complete
$classificationSpan->end();
```

### Generations

Generations track individual LLM calls and their results.

```php
# Create a generation within a span
$generation = $classificationSpan->generation('model-call')
    ->withModel('gpt-4.1-mini')
    ->withModelParameters(['temperature' => 0])
    ->setInput(['role' => 'user', 'content' => 'Hello there'])
    ->setOutput('AI response here');
```

### Flushing Data

Ensure all data is sent to Langfuse before your application terminates.

```php
$result = $profiler->flush();
```

## Laravel
```php
class LangfuseProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LangfuseClient::class, function ($app) {
            return new LangfuseClient(
                # Your variables from .env
                config('services.langfuse.public_key'),
                config('services.langfuse.secret_key'),
                config('services.langfuse.base_uri')
            );
        });

        $this->app->singleton(LangfuseProfiler::class, function ($app) {
            return new LangfuseProfiler($app->make(LangfuseClient::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
```

## Advanced Usage

### Working with Multiple Generations

```php
# Create multiple generations within a span
$generation1 = $trace->span('classification')->generation('generation-1')
    ->setModel('gpt-4.1-mini')
    ->setModelParameters(['temperature' => 0.5])
    ->setPrompt($prompt)
    ->setInput($messages)
    ->setOutput('RESULT_1');

$generation2 = $trace->span('classification')->generation('generation-2')
    ->setModel('gpt-4.1-nano')
    ->setModelParameters(['temperature' => 0])
    ->setPrompt($prompt)
    ->setInput($messages)
    ->setOutput('RESULT_2');
```
