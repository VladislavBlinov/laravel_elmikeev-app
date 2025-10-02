<?php
namespace App\Services;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class ApiFetcher
{
    protected ConsoleOutput $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    protected function buildHttpClient($account, string $tokenType, string $token)
    {
        $http = Http::timeout(30)->retry(5, 3000);

        return match ($tokenType)
        {
            'bearer' => $http->withToken($token),
            'api-key' => $http,
            'login-password' => $http->withBasicAuth($account->name, $token),
            default => throw new Exception("Неизвестный тип токена: {$tokenType}"),
        };
    }

    protected function fetchResponse($http, string $url, array $params, int &$retryCount, int $maxRetries): array
    {
        $response = $http->get($url, $params);

        $status = $response->status();

        if ($status === 429)
        {
            $retryAfter = $response->header('Retry-After') ?? 60;
            $this->output->writeln("Лимит запросов, ждем {$retryAfter} сек..");
            sleep((int)$retryAfter);

            $retryCount++;
            if ($retryCount>=$maxRetries)
            {
                throw new Exception("Превышено число попыток при ошибке 429");
            }

            return $this->fetchResponse($http, $url, $params, $retryCount, $maxRetries);
        }

        if (in_array($status, [
            502,
            503,
            504
        ]))
        {
            $this->output->writeln("Сервер перегружен ({$status}), ждем 30 сек...");
            sleep(30);

            $retryCount++;
            if ($retryCount>=$maxRetries)
            {
                throw new Exception("Превышено число попыток при ошибках сервера");
            }

            return $this->fetchResponse($http, $url, $params, $retryCount, $maxRetries);
        }

        if ($response->failed())
        {
            throw new Exception("Ошибка сервера, статус: {$status}");
        }

        $retryCount = 0;

        return $response->json();
    }

    protected function saveBatch(string $model, array $batch, array $uniqueBy, array $updateFields, string $endpoint)
    {
        try
        {
            $model::upsert($batch, $uniqueBy, $updateFields);
            $this->output->writeln("Сохранено " . count($batch) . " записей в {$model}");
        }
        catch (Throwable $t)
        {
            throw new Exception("Ошибка при сохранении в БД {$endpoint}: " . $t->getMessage());
        }
    }

    public function fetchAndSave(
        int $accountId,
        string $endpoint,
        array $params,
        string $model,
        array $uniqueBy,
        array $updateFields,
        callable $mapCallback
    )
    {
        try
        {
            $account = Account::with([
                'tokens.service',
                'tokens.tokenType'
            ])
                ->findOrFail($accountId)
            ;

            $apiToken = $account->tokens->firstOrFail();

            $service = $apiToken->service;
            $tokenType = $apiToken->tokenType->name;
            $token = $apiToken->token;

            $page = 1;
            $limit = 500;
            $retryCount = 0;
            $maxRetries = 5;

            $http = $this->buildHttpClient($account, $tokenType, $token);

            while (true)
            {
                $requestParams = $params;
                if ($tokenType === 'api-key')
                {
                    $requestParams['key'] = $token;
                }

                $requestParams = array_merge($requestParams, [
                    'page' => $page,
                    'limit' => $limit
                ]);
                $data = $this->fetchResponse($http, $service->base_url . "/{$endpoint}", $requestParams, $retryCount, $maxRetries);

                if (empty($data['data']))
                {
                    break;
                }

                $batch = [];
                foreach ($data['data'] as $item)
                {
                    $batch[] = $mapCallback($item, $accountId);

                    if (count($batch)>=100)
                    {
                        $this->saveBatch($model, $batch, $uniqueBy, $updateFields, $endpoint);
                        $batch = [];
                        gc_collect_cycles();
                    }
                }

                if (!empty($batch))
                {
                    $this->saveBatch($model, $batch, $uniqueBy, $updateFields, $endpoint);
                    unset($batch);
                    gc_collect_cycles();
                }

                unset($data);
                $page++;
                sleep(1.2);
            }
        }
        catch (Throwable $e)
        {
            throw $e;
        }
    }
}
