<?php

namespace App\Helpers;

use App\Models\Account;

class DateRangeHelper
{
    public static function resolveDates(string $model, int $accountId, ?string $dateFrom, ?string $dateTo): array
    {
        if (!$dateFrom)
        {
            $account = Account::findOrFail($accountId);
            $lastDate = $model::where('account_id', $accountId)->max('date');
            $dateFrom = $lastDate
                ? date('Y-m-d', strtotime($lastDate))
                : '2000-01-01';
        }

        $dateTo = $dateTo ?? date('Y-m-d');

        return [
            $dateFrom,
            $dateTo
        ];
    }
}
