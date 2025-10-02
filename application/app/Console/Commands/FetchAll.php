<?php

namespace App\Console\Commands;

use App\Helpers\AccountHelper;
use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:all {account_id?} {--account-all} {date_from?} {date_to?}';

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
        $dateFrom = $this->argument('date_from');
        $dateTo = $this->argument('date_to');

        $accountId = $this->argument('account_id');
        $accountAll = $this->option('account-all');

        $accounts = AccountHelper::resolveAccounts($accountId, $accountAll);

        if ($accounts->isEmpty())
        {
            $this->warn('Нет аккаунтов для обработки');

            return 0;
        }

        try
        {
            foreach ($accounts as $account)
            {
                $apiToken = $account->tokens->first();

                if (!$apiToken)
                {
                    $this->warn("Токен для аккаунта {$accountId} не найден");

                    continue;
                }

                $allowed = \App\Models\ApiServiceTokenType::where('api_service_id', $apiToken->api_service_id)
                    ->where('token_type_id', $apiToken->token_type_id)
                    ->exists()
                ;

                if (!$allowed)
                {
                    $this->warn("Тип токена {$apiToken->tokenType->name} не разрешен для сервиса {$apiToken->service->name}");

                    continue;
                }

                $this->info("Обработка аккаунта {$account->name}");
                $this->call('fetch:sales', [
                    'account_id' => $account->id,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]);
                $this->call('fetch:orders', [
                    'account_id' => $account->id,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]);
                $this->call('fetch:incomes', [
                    'account_id' => $account->id,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]);
                $this->call('fetch:stocks', [
                    'account_id' => $account->id,
                ]);
            }

            $this->info('Все данные получены!');

            return 0;
        }

        catch (\Exception $e)
        {
            $this->error($e->getMessage());

            return 1;
        }
    }
}






