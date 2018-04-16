<?php
declare(strict_types=1);

namespace App\BusinessObjects;

class Withdrawal
{
    private $amount;

    private $limit;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit ?? 1000;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }
}