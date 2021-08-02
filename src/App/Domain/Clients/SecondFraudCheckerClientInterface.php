<?php

declare(strict_types=1);

namespace App\Domain\Clients;

use App\Domain\Entities\Transaction;

/**
 * @return string
 */
interface SecondFraudCheckerClientInterface
{
    public function check(Transaction $Transaction): bool;
}
