<?php

namespace BlahteSoftware\BsPaypal\Traits;

trait RequestIdTrait {
    private static $PREFIX = 'RIN';
    private static $CODE;

    public function getRequestNumber(int $lastId = 0): string {
        $n = (string) $lastId;
        self::$CODE = self::$PREFIX . str_pad($n, 6, '0', STR_PAD_LEFT);
        return self::$CODE;
    }
}