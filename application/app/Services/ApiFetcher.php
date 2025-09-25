<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class ApiFetcher
{
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
        $limit = 100;

        while (true)
        {
            $response = Http::retry(3, 3000)->get(config('api.base_url') . "/{$endpoint}", array_merge($params, [
                'page' => $page,
                'limit' => $limit,
                'key' => config('api.api_key'),
            ]));

            if ($response->failed())
            {
                throw new Exception("Ошибка, статус: " . $response->status());
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
            }

            $page++;
            sleep(2);

        }
    }
}
