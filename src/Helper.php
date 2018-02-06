<?php
namespace Batmahir\Laravelxero;

trait Helper
{
    /**
     * Genereate random string
     *
     * @param int $length
     * @return string
     *
     */
    public function getNonce($length = 5)
    {
        $alphabets = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $result = '';
        $cLength = strlen($alphabets);
        for ($i=0; $i < $length; $i++)
        {
            $rnum = rand(0,$cLength);
            $result .= substr($alphabets,$rnum,1);
        }

        return $result;
    }

    /**
     * Turn any array to url query string
     *
     * @param array $array
     * @return bool|string
     */
    public function turnArrayToUrlQuery(array $array)
    {
        if(!count($array))
        {
            return '';
        }

        $mergedString = '';

        foreach($array as $key => $value)
        {
            $mergedString .= $key.'='.urlencode($value).'&';
        }

        $mergedString = substr($mergedString, 0, -1); // to remove the last part of string

        return $mergedString;
    }

    public function turnToXeroFormatForSignatureData($request_method , $url ,$parameter)
    {
        $combinedString = $request_method.'&'.urlencode($url).'&'.urlencode($parameter);

        return $combinedString;
    }

    public function turnToFullUrl($url,$parameter)
    {
        $fullUrl = $url.'?'.$parameter;

        return $fullUrl;
    }

    public function generateSignature($mixedCombinedString,$combined_secret)
    {
        return base64_encode(hash_hmac('sha1',$mixedCombinedString,$combined_secret ,true));
    }
}