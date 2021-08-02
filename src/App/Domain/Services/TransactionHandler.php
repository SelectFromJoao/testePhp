<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Entities\Notification;
use App\Domain\Repositories\TransactionRepositoryInterface;
use App\Domain\Exceptions\FraudCheckerException;
use DateTime;
use Exception;

class TransactionHandler
{

    /**
     * @var TransactionRepositoryInterface
     */
    private TransactionRepositoryInterface $repository;

    /**
     * @var TaxCalculator
     */
    private TaxCalculator $taxCalculator;

    /**
     * @var FraudChecker
     */
    private FraudChecker $fraudChecker;

    /**
     * @var Notifier
     */
    private Notifier $notifier;

    /**
     * @var TransactionData
     */
    private TransactionDataFill $TransactionData;

    public function __construct(
        TransactionRepositoryInterface $repository,
        FraudChecker $fraudChecker,
        Notifier $notifier,
        TransactionDataFill $TransactionData
    )
    {
        $this->repository = $repository;
        $this->fraudChecker = $fraudChecker;
        $this->notifier = $notifier;
        $this->TransactionData = $TransactionData;
    }

    /**
     * @throws Exception
     */
    public function create(Transaction $transaction, notification $notifyBuyer, notification $notifySeller  ): Transaction
    {
        if (!$this->fraudChecker->check($transaction)) {
            throw new FraudCheckerException;
        }
        
        $this->TransactionData->FillDataTransaction($transaction);

        $persistTransaction =  $this->repository->save($transaction);

        $this->notifier->notifyBuyer($transaction->getBuyer(), $notifyBuyer);
        $this->notifier->notifySeller($transaction->getSeller(), $notifySeller);

        return $persistTransaction;
    }
}
