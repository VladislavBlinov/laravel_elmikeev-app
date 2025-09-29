<?php
namespace App\Services;

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

    public function fetchAndSave(
        string $endpoint,
        array $params,
        string $model,
        array $uniqueBy,
        array $updateFields,
        callable $mapCallback
    )
    {
        $page = 1;
        $limit = 200;
        $retryCount = 0;
        $maxRetries = 5;

        while (true)
        {
            $response = Http::timeout(30)
                ->retry(5, 1200)
                ->get(config('api.base_url') . "/{$endpoint}", array_merge($params, [
                    'page' => $page,
                    'limit' => $limit,
                    'key' => config('api.api_key'),
                ]))
            ;

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

            $rows = array_map($mapCallback, $data['data']);
            foreach (array_chunk($rows, 500) as $batch)
            {
                $model::upsert($batch, $uniqueBy, $updateFields);
                $this->output->writeln("Сохранено " . count($batch) . " записей в {$model}");
            }

            $page++;
            sleep(1.2);
        }
    }
}
