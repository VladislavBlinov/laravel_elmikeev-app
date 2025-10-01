<?php

namespace App\Helpers;

use App\Models\Account;
use Illuminate\Support\Collection;

class AccountHelper
{
    /**
     * Возвращает коллекцию аккаунтов для обработки.
     *
     * @param int|null $accountId
     * @param bool $all
     * @return Collection<Account>
     */
    public static function resolveAccounts(?int $accountId, bool $all): Collection
    {
        if ($all)
        {
            return Account::all();
        }

        if (!$accountId)
        {
            return collect();
        }

        return Account::where('id', $accountId)->get();
    }
}
