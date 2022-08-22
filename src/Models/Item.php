<?php

namespace BlahteSoftware\BsPaypal\Models;

use BlahteSoftware\BsPaypal\Coverters\FormatConverter;
use BlahteSoftware\BsPaypal\Validators\NumericValidator;
use BlahteSoftware\BsPaypal\Validators\UrlValidator;

/**
 * Class Item
 *
 * Item details.
 *
 * @property string sku
 * @property string name
 * @property string description
 * @property string quantity
 * @property string price
 * @property string currency
 * @property string tax
 * @property string url
 */
class Item extends BaseModel {
    /**
     * Stock keeping unit corresponding (SKU) to item.
     *
     * @param string $sku
     * 
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Stock keeping unit corresponding (SKU) to item.
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Item name. 127 characters max.
     *
     * @param string $name
     * 
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Item name. 127 characters max.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Description of the item. Only supported when the `payment_method` is set to `paypal`.
     *
     * @param string $description
     * 
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Description of the item. Only supported when the `payment_method` is set to `paypal`.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Number of a particular item. 10 characters max.
     *
     * @param string $quantity
     * 
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Number of a particular item. 10 characters max.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Item cost. 10 characters max.
     *
     * @param string|double $price
     * 
     * @return $this
     */
    public function setPrice($price)
    {
        NumericValidator::validate($price, "Price");
        $price = FormatConverter::formatToPrice($price, $this->getCurrency());
        $this->price = $price;
        return $this;
    }

    /**
     * Item cost. 10 characters max.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
     *
     * @param string $currency
     * 
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Tax of the item. Only supported when the `payment_method` is set to `paypal`.
     *
     * @param string|double $tax
     * 
     * @return $this
     */
    public function setTax($tax)
    {
        NumericValidator::validate($tax, "Tax");
        $tax = FormatConverter::formatToPrice($tax, $this->getCurrency());
        $this->tax = $tax;
        return $this;
    }

    /**
     * Tax of the item. Only supported when the `payment_method` is set to `paypal`.
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * URL linking to item information. Available to payer in transaction history.
     *
     * @param string $url
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setUrl($url)
    {
        UrlValidator::validate($url, "Url");
        $this->url = $url;
        return $this;
    }

    /**
     * URL linking to item information. Available to payer in transaction history.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
