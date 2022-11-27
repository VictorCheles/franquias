<?php

namespace App\Custom;

use CouponCode\CouponCode as cc;

class CouponCode extends cc
{
    public function __construct()
    {
        $config = [
            'parts' => 2,
            'partLength' => 3,
        ];
        parent::__construct($config);
    }
}
