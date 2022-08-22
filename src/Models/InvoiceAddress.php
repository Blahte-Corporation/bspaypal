<?php

namespace BlahteSoftware\BsPaypal\Models;

/**
 * Class InvoiceAddress
 *
 * Base Address object used as billing address in a payment or extended for Shipping Address.
 *
 *
 * @property \BlahteSoftware\BsPaypal\Models\Phone phone
 */
class InvoiceAddress extends BaseAddress {
    /**
     * Phone number in E.123 format.
     *
     * @param \BlahteSoftware\BsPaypal\Models\Phone $phone
     * 
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Phone number in E.123 format.
     *
     * @return \BlahteSoftware\BsPaypal\Models\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
