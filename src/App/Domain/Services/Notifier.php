<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Clients\NotifierClientInterface;
use App\Domain\Entities\Notification;
use App\Domain\Entities\Buyer;
use App\Domain\Entities\Seller;
use App\Domain\Entities\Transaction;
use App\Domain\Exceptions\NotifierException;

class Notifier
{
    /**
     * @var NotifierClientInterface
     */
    private NotifierClientInterface $client;

    public function __construct(NotifierClientInterface $client)
    {
        $this->client = $client;
    }

    public function notifyBuyer(Buyer $Buyer,  Notification $notification){
        $notification->setEmail($Buyer->getEmail());
        if (!$this->client->notify($notification)){
            throw new NotifierException();
        }
    }

    public function notifySeller(Seller $Seller,  Notification $notification){
        $notification->setEmail($Seller->getEmail());
        if (!$this->client->notify($notification)){
            throw new NotifierException();
        }
    }
}
