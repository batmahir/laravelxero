<?php

namespace Batmahir\Laravelxero;

use Batmahir\Laravelxero\Helper;
use Ixudra\Curl\Facades\Curl;

class XeroRequest extends XeroOAuth
{

    public static function xeroCallback()
    {
        $xero = new XeroOAuth();
        $request = $xero->request()->all();
        $xero->setOAuthAttribute($request);
        return $xero->accessToken();

    }

    public static function to($url,array $url_query_array = array())
    {
        $xeroRequest = new XeroRequest();
        $xeroRequest->getNormalRequestFullUrl($url,$url_query_array);
        return Curl::to($url);
    }

    public function getNormalRequestFullUrl($url,$url_query_array)
    {
        $this->full_url_to_be_request = $url;
        $this->getNormalRequestArray();
    }


}
