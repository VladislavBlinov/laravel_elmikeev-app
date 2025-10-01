<?php

namespace App\Console\Commands;

use App\Helpers\DateRangeHelper;
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
    protected $signature = 'fetch:incomes {account_id} {date_from?} {date_to?}';

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
        $accountId = (int)$this->argument('account_id');

        [
            $dateFrom,
            $dateTo
        ] = DateRangeHelper::resolveDates(
            Income::class,
            $accountId,
            $this->argument('date_from'),
            $this->argument('date_to')
        );

        $this->info("Начинаем загрузку incomes");

        try
        {
            $fetcher->fetchAndSave(
                $accountId,
                'api/incomes',
                [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ],
                Income::class,
                [
                    'account_id',
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
                    'account_id' => $accountId,
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

            return 0;
        }
        catch (\Exception $e)
        {
            $this->error('Исключение в incomes: ' . $e->getMessage());
        }
    }
}
