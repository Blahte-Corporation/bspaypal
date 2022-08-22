<?php

namespace BlahteSoftware\BsPaypal\Models;

use BlahteSoftware\BsPaypal\Models\BaseModel;

/**
 * Class ShippingCost
 *
 * Shipping cost, as a percent or an amount.
 *
 *
 * @property \BlahteSoftware\BsPaypal\Models\Currency amount
 * @property \BlahteSoftware\BsPaypal\Models\Tax tax
 */
class ShippingCost extends BaseModel
{
    /**
     * The shipping cost, as an amount. Valid range is from 0 to 999999.99.
     *
     * @param \BlahteSoftware\BsPaypal\Models\Currency $amount
     * 
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * The shipping cost, as an amount. Valid range is from 0 to 999999.99.
     *
     * @return \BlahteSoftware\BsPaypal\Models\Currency
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * The tax percentage on the shipping amount.
     *
     * @param \BlahteSoftware\BsPaypal\Models\Tax $tax
     * 
     * @return $this
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * The tax percentage on the shipping amount.
     *
     * @return \BlahteSoftware\BsPaypal\Models\Tax
     */
    public function getTax()
    {
        return $this->tax;
    }
}
