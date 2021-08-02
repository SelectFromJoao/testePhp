<?php

declare(strict_types=1);

namespace App\Domain\Clients;

use App\Domain\Entities\Notification;

/**
 * Interface TaxManagerClientInterface
 * @package App\Domain\Clients
 */

/**
 * @return boolean
 */
interface NotifierClientInterface
{
    public function notify(Notification $notifier): bool;
}
