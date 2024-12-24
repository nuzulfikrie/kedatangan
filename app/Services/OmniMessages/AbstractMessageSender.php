<?php

namespace App\Services\OmniMessages;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\MessageException;

abstract class AbstractMessageSender implements IMessageSenderInterface
{
    protected string $apiKey;
    protected array $config;
    protected const MAX_RETRIES = 3;
    protected const CACHE_TTL = 3600; // 1 hour

    public function __construct(string $apiKey, array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->config = $config;
    }

    abstract protected function getServiceName(): string;

    protected function validateApiKey(): void
    {
        if (empty($this->apiKey)) {
            throw new MessageException('API Key is required for message sending.');
        }
    }

    protected function logMessage(string $action, array $data = [], string $level = 'info'): void
    {
        $context = array_merge([
            'service' => $this->getServiceName(),
            'timestamp' => now()->toIso8601String()
        ], $data);

        Log::channel('messages')->{$level}("[{$this->getServiceName()}] {$action}", $context);
    }

    protected function cacheSet(string $key, $value, ?int $ttl = null): void
    {
        $cacheKey = "{$this->getServiceName()}:{$key}";
        Cache::put($cacheKey, $value, $ttl ?? $this::CACHE_TTL);
    }

    protected function cacheGet(string $key)
    {
        $cacheKey = "{$this->getServiceName()}:{$key}";
        return Cache::get($cacheKey);
    }

    protected function withRetry(callable $operation, int $maxRetries = null): mixed
    {
        $retries = 0;
        $maxRetries = $maxRetries ?? self::MAX_RETRIES;

        while ($retries < $maxRetries) {
            try {
                return $operation();
            } catch (\Exception $e) {
                $retries++;
                $this->logMessage(
                    'Operation failed, attempting retry',
                    [
                        'error' => $e->getMessage(),
                        'attempt' => $retries,
                        'max_retries' => $maxRetries
                    ],
                    'warning'
                );

                if ($retries === $maxRetries) {
                    throw $e;
                }

                sleep(min(pow(2, $retries), 10)); // Exponential backoff
            }
        }
    }
}
