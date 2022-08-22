<?php

namespace BlahteSoftware\BsPaypal;

use Exception;

use function BlahteSoftware\BsPaypal\Utils\findObjectByPropertyValue;

class CreateOrderResponse {
    protected object $data;

    public function __construct(object $data) {
        $this->data = $data;
    }

    public function getApprovalUrl() : string {
        if(! property_exists($this->data, 'links')) {
            throw new Exception("Link not found!");
        }
        $link = findObjectByPropertyValue($this->data->links, 'rel', 'approve');
        if(is_null($link)) {
            throw new Exception("Link not found.");
        }
        if(! property_exists($link, 'href') ) {
            throw new Exception("Link not found.");
        }
        return $link->href;
    }

    public function getCaptureUrl() : string {
        if(! property_exists($this->data, 'links')) {
            throw new Exception("Link not found!");
        }
        $link = findObjectByPropertyValue($this->data->links, 'rel', 'capture');
        if(is_null($link)) {
            throw new Exception("Link not found.");
        }
        if(! property_exists($link, 'href') ) {
            throw new Exception("Link not found.");
        }
        return $link->href;
    }

    public function getUpdateUrl() : string {
        if(! property_exists($this->data, 'links')) {
            throw new Exception("Link not found!");
        }
        $link = findObjectByPropertyValue($this->data->links, 'rel', 'update');
        if(is_null($link)) {
            throw new Exception("Link not found.");
        }
        if(! property_exists($link, 'href') ) {
            throw new Exception("Link not found.");
        }
        return $link->href;
    }

    public function getInfoUrl() : string {
        if(! property_exists($this->data, 'links')) {
            throw new Exception("Link not found!");
        }
        $link = findObjectByPropertyValue($this->data->links, 'rel', 'self');
        if(is_null($link)) {
            throw new Exception("Link not found.");
        }
        if(! property_exists($link, 'href') ) {
            throw new Exception("Link not found.");
        }
        return $link->href;
    }

    public function getOrderId() : string {
        return $this->data->id;
    }

    public function getIntent() : string {
        return $this->data->intent;
    }

    public function getStatus() : string {
        return $this->data->status;
    }
}
