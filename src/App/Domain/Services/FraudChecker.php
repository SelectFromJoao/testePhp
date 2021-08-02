<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Clients\FirstFraudCheckerClientInterface;
use App\Domain\Clients\SecondFraudCheckerClientInterface;
use App\Domain\Exceptions\FraudCheckerException;

class FraudChecker
{
    /**
     * @var FirstFraudCheckerClientInterface
     */
    private FirstFraudCheckerClientInterface $FirstFraudCheckerClientInterface;

    /**
     * @var SecondFraudCheckerClientInterface
     */
    private SecondFraudCheckerClientInterface $SecondFraudCheckerClientInterface;


    public function __construct(
        FirstFraudCheckerClientInterface $FirstFraudCheckerClientInterface,
        SecondFraudCheckerClientInterface $SecondFraudCheckerClientInterface
    )
    {
        $this->FirstFraudCheckerClientInterface = $FirstFraudCheckerClientInterface;
        $this->SecondFraudCheckerClientInterface = $SecondFraudCheckerClientInterface;
    }

    public function check(Transaction $transaction): bool
    {   
        $auth = true;
        if (!$this->FirstFraudCheckerClientInterface->Check($transaction)) {
            if(!$this->SecondFraudCheckerClientInterface->Check($transaction)){
                $auth = false;
            }
        }
        return $auth;
    }
}
