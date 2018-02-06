<?php

namespace Batmahir\Laravelxero;

use Batmahir\Laravelxero\Helper;
use Ixudra\Curl\Facades\Curl;

class XeroRequest extends XeroOAuth
{

    public static function to($url)
    {
        return Curl::to($url);
    }


}
