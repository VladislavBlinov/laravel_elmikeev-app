<?php
namespace App\Services;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;

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

        switch ($tokenType)
        {
            case 'bearer':
                return $http->withToken($token);
            case 'api-key':
                return $http;
            case 'login-password':
                return $http->withBasicAuth($account->login, $token);
            default:
                throw new Exception("Неизвестный тип токена: {$tokenType}");
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
        $account = Account::with([
            'tokens.service',
            'tokens.tokenType'
        ])->findOrFail($accountId);

        $apiToken = $account->tokens->firstOrFail();
        $service = $apiToken->service;
        $tokenType = $apiToken->tokenType->name;
        $token = $apiToken->token;

        $page = 1;
        $limit = 500;
        $retryCount = 0;
        $maxRetries = 5;

        while (true)
        {
            $http = $this->buildHttpClient($account, $tokenType, $token);

            if ($tokenType === 'api-key')
            {
                $params['key'] = $token;
            }

            $response = $http->get($service->base_url . "/{$endpoint}", array_merge($params, [
                'page' => $page,
                'limit' => $limit,
            ]));

            if ($response->status() === 429)
            {
                $retryAfter = $response->header('Retry-After') ?? 60;
                $this->output->writeln("Лимит запросов, ждем {$retryAfter} сек..");
                sleep((int)$retryAfter);

                $retryCount++;
                if ($retryCount>=$maxRetries)
                {
                    throw new Exception("Превышено число попыток при ошибке 429");
                }

                continue;
            }

            $retryCount = 0;

            if (in_array($response->status(), [
                502,
                503,
                504
            ]))
            {
                $this->output->writeln("Сервер перегружен ({$response->status()}), ждем 30 сек...");
                sleep(30);

                $retryCount++;
                if ($retryCount>=$maxRetries)
                {
                    throw new Exception("Превышено число попыток при ошибках сервера");
                }

                continue;
            }

            $retryCount = 0;

            if ($response->failed())
            {
                throw new Exception("Ошибка сервера, статус: " . $response->status());
            }

            $data = $response->json();
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
                    try
                    {
                        $model::upsert($batch, $uniqueBy, $updateFields);
                        $this->output->writeln("Сохранено " . count($batch) . " записей в {$model}");
                        $batch = [];
                        gc_collect_cycles();
                    }
                    catch (Throwable $t)
                    {
                        throw new Exception("Ошибка при сохранении в БД {$endpoint}: " . $t->getMessage());
                    }
                }
            }

            if (!empty($batch))
            {
                try
                {
                    $model::upsert($batch, $uniqueBy, $updateFields);
                    $this->output->writeln("Сохранено " . count($batch) . " записей в {$model}");
                    unset($batch);
                    gc_collect_cycles();
                }
                catch (Throwable $t)
                {
                    throw new Exception("Ошибка при сохранении в БД {$endpoint}: " . $t->getMessage());
                }
            }

            unset($data);

            $page++;
            sleep(1.2);
        }
    }
}
