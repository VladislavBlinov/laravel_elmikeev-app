<?php

namespace App\Console\Commands;

use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:all {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт всех данных из API';

    /**
     * Execute the console command.
     */
    public function handle(ApiFetcher $fetcher)
    {
        $dateFrom = $this->argument('dateFrom') ?? '2000-01-01';
        $dateTo = $this->argument('dateTo') ?? date('Y-m-d');

        try
        {
            $this->call('fetch:sales', [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
            $this->call('fetch:orders', [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
            $this->call('fetch:incomes', [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
            $this->call('fetch:stocks');

            $this->info('Все данные получены!');
        }

        catch (\Exception $e)
        {
            $this->error($e->getMessage());
        }
    }
}






