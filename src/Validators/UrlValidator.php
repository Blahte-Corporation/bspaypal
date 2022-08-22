<?php

namespace BlahteSoftware\BsPaypal\Validators;

class UrlValidator {
    /**
     * @param $url
     * @param string|null $urlName
     * @throws \InvalidArgumentException
     */
    public static function validate($url, $urlName = null) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("$urlName is not a fully qualified URL");
        }
    }
}
