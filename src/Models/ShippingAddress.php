<?php

namespace BlahteSoftware\BsPaypal\Models;

/**
 * Class ShippingAddress
 *
 * Extended Address object used as shipping address in a payment.
 *
 *
 * @property string recipient_name
 */
class ShippingAddress extends Address
{
    /**
     * Name of the recipient at this address.
     *
     * @param string $recipient_name
     * 
     * @return $this
     */
    public function setRecipientName($recipient_name)
    {
        $this->recipient_name = $recipient_name;
        return $this;
    }

    /**
     * Name of the recipient at this address.
     *
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipient_name;
    }
}
