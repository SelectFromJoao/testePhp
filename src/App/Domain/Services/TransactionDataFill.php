<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Utils\SlytherinDate;
use App\Domain\Uitls\SlytherinGuid;

class TransactionDataFill
{
    /**
     * @var TaxCalculator
     */
    private TaxCalculator $taxCalculator;

    /**
     * @var SlytherinDate
     */
    private SlytherinDate $SlytherinDate;

    /**
     * @var SlytherinGuid
     */
    private SlytherinGuid $SlytherinGuid;

    public function __construct(
        TaxCalculator $taxCalculator,
        SlytherinDate $slytherinDate,
        SlytherinGuid $slytherinGuid
    )
    {
        $this->taxCalculator = $taxCalculator;
        $this->SlytherinDate = $slytherinDate;
        $this->SlytherinGuid = $slytherinGuid;
    }

    public function FillDataTransaction(Transaction $transaction)
    {
        $totalValueComTaxas = $this->taxCalculator->calculate($transaction->getInitialAmount(), $transaction->getSellerTax());
        $SlytherinPayTax = $this->taxCalculator->calculateSlytherinPayTax($totalValueComTaxas, $transaction->getInitialAmount(), $transaction->getSellerTax());
        $transaction->setId($this->SlytherinGuid->Guid());
        $transaction->setTotalTax($SlytherinPayTax + $transaction->getSellerTax());
        $transaction->setSlytherinPayTax($SlytherinPayTax);
        $transaction->setTotalAmount($totalValueComTaxas);
        $transaction->setCreatedDate($this->SlytherinDate->currentTime());
    }
}
