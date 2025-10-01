<?php

namespace App\Helpers;

class DateRangeHelper
{
    public static function resolveDates(string $model, int $accountId, ?string $dateFrom, ?string $dateTo): array
    {
        if (!$dateFrom)
        {
            $lastDate = $model::where('account_id', $accountId)->max('date');
            $dateFrom = $lastDate
                ? date('Y-m-d', strtotime($lastDate . ' +1 day'))
                : '2000-01-01';
        }

        $dateTo = $dateTo ?? date('Y-m-d');

        return [
            $dateFrom,
            $dateTo
        ];
    }
}
