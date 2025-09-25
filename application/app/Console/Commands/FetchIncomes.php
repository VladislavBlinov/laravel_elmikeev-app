<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:incomes {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт incomes из API';

    /**
     * Execute the console command.
     */
    public function handle(ApiFetcher $fetcher)
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');

        try
        {
            $fetcher->fetchAndSave(
                'incomes',
                [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ],
                Income::class,
                [
                    'income_id',
                    'nm_id',
                    'date',
                ],
                [
                    'number',
                    'last_change_date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'quantity',
                    'total_price',
                    'date_close',
                    'warehouse_name',
                ],
                fn($item) => [
                    'income_id' => $item['income_id'] ?? null,
                    'number' => $item['number'] ?? null,
                    'date' => $item['date'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'quantity' => $item['quantity'] ?? null,
                    'total_price' => $item['total_price'] ?? null,
                    'date_close' => $item['date_close'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                ]
            );
            $this->info('incomes загружен!');
        }
        catch (\Exception $e)
        {
            $this->error($e->getMessage());
        }
    }
}
