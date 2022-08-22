<?php

namespace BlahteSoftware\BsPaypal\Models;

use BlahteSoftware\BsPaypal\Coverters\FormatConverter;
use BlahteSoftware\BsPaypal\Validators\NumericValidator;

/**
 * Class Tax
 *
 * Tax information.
 *
 *
 * @property string id
 * @property string name
 * @property \BlahteSoftware\BsPaypal\Models\Currency amount
 */
class Tax extends BaseModel {
    /**
     * The resource ID.
     *
     * @param string $id
     * 
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * The resource ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The tax name. Maximum length is 20 characters.
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
     * The tax name. Maximum length is 20 characters.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The rate of the specified tax. Valid range is from 0.001 to 99.999.
     *
     * @param string|double $percent
     * 
     * @return $this
     */
    public function setPercent($percent)
    {
        NumericValidator::validate($percent, "Percent");
        $percent = FormatConverter::formatToPrice($percent);
        $this->percent = $percent;
        return $this;
    }

    /**
     * The rate of the specified tax. Valid range is from 0.001 to 99.999.
     *
     * @return string
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * The tax as a monetary amount. Cannot be specified in a request.
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
     * The tax as a monetary amount. Cannot be specified in a request.
     *
     * @return \BlahteSoftware\BsPaypal\Models\Currency
     */
    public function getAmount()
    {
        return $this->amount;
    }

}
