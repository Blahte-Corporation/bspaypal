<?php

namespace BlahteSoftware\BsPaypal\Models;

use BlahteSoftware\BsPaypal\Models\ItemList;

class PurchaseUnit {
    protected ItemList $items;
    protected Amount $amount;

    public function __construct()
    {
        $this->items = new ItemList();
        $this->amount = new Amount();
    }

    public function setItems(ItemList $items) : PurchaseUnit {
        $this->items = $items;
        return $this;
    }

    public function getItems() : array {
        return $this->items->toArray();
    }

    public function setAmount(Amount $amount) : PurchaseUnit {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount() : array {
        return $this->amount->toArray();
    }
}
