<?php

declare(strict_types=1);

namespace App\Domain\Clients;

use App\Domain\Entities\Transaction;

interface FirstFraudCheckerClientInterface
{
    public function check(Transaction $Transaction): bool;
}
