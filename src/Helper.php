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

    public function appendParameterToUrlQuery($paramter_query,array $parameter_array)
    {
        if(!count($parameter_array))
        {
            return $paramter_query;
        }
        $mergedString = '';

        foreach($parameter_array as $key => $value)
        {
            $mergedString .= $key.'='.urlencode($value).'&';
        }

        $mergedString = substr($mergedString, 0, -1); // to remove the last part of string


        $paramter_query .= '&'.$mergedString;

        return $paramter_query;
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

    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    public function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->app('request');
        }

        if (is_array($key)) {
            return $this->app('request')->only($key);
        }

        $value = $this->app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }


    /**
     *  Get the available container instance.
     *
     * @param null $abstract
     * @param array $parameters
     * @return mixed
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }

        return empty($parameters)
            ? \Illuminate\Container\Container::getInstance()->make($abstract)
            : \Illuminate\Container\Container::getInstance()->makeWith($abstract, $parameters);
    }


}