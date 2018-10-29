<?php

namespace Batmahir\Laravelxero;

use Ixudra\Curl\Facades\Curl;

class Xero extends XeroMainParent
{

    public function __construct()
    {
        parent::__construct();
    }

    public function requestToken()
    {
        $this->getRequestTokenArray()->getMandatoryArray();

        $this->main_endpoint_to_be_request = $this->request_token_endpoint;

        $this->parameterWithoutSignature = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->assignSignatureToAttribute();

        $parameter_UrlQuery = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->url_parameter = $this->appendParameterToUrlQuery($parameter_UrlQuery,['oauth_signature' => $this->signature]);
        $this->full_url_to_be_request = $this->turnToFullUrl($this->request_token_endpoint,$this->url_parameter);

        return $this;

    }

    public function sendGetRequestForRequestToken()
    {
        $this->requestToken();

        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }

        $requestTokenResponse = Curl::to($this->full_url_to_be_request)->get();

        parse_str($requestTokenResponse, $requestTokenResponseArray);

        if(isset($requestTokenResponseArray['oauth_problem']))
        {
            throw new LaravelXeroException($requestTokenResponseArray['oauth_problem'].','.$requestTokenResponseArray['oauth_problem_advice']);
        }

        $this->oauth_token = $requestTokenResponseArray['oauth_token'];
        $this->oauth_secret = $requestTokenResponseArray['oauth_token_secret'];
        $this->combinedSecret = $this->combinedSecret.$this->oauth_secret;

        return $this;
    }

    public static function  authorize($direct_redirect = true)
    {
        $xero = new Xero();
        $xero->sendGetRequestForRequestToken();

        $xero->full_url_to_be_request = $xero->authorization_endpoint.'?oauth_token='.$xero->oauth_token.'&scope=';

        if(!isset($xero->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }
        
        $request = $xero->request();
        $xero->session(
            [
                'xero' => $xero->getAllAttribute()
            ]
        );

        $request->session()->save();

        if($direct_redirect == true)
        {
            $xero->redirect($xero->full_url_to_be_request);
        }

        return $xero->full_url_to_be_request;

    }

    public function redirect($url)
    {
        header('Location: '.$url);
    }

    public function accessToken()
    {
        $this->main_endpoint_to_be_request = $this->access_token_endpoint;

        $this->parameterWithoutSignature =
            "oauth_consumer_key=".$this->consumer_key.
            "&oauth_nonce=".$this->nonce.
            "&oauth_signature_method=".$this->oauth_signature_method.
            "&oauth_timestamp=".$this->time.
            "&oauth_token=".$this->oauth_token.
            "&oauth_verifier=".$this->oauth_verifier.
            "&oauth_version=".$this->oauth_version;

        $this->combinedString = $this->turnToXeroFormatForSignatureData("GET",$this->main_endpoint_to_be_request,$this->parameterWithoutSignature);

        $this->assignSignatureToAttribute();
        $this->url_parameter = $this->appendParameterToUrlQuery($this->parameterWithoutSignature,['oauth_signature' => $this->signature]);
        $this->full_url_to_be_request = $this->turnToFullUrl($this->main_endpoint_to_be_request,$this->url_parameter);


        $accessTokenResponse = Curl::to($this->full_url_to_be_request)->get();

        parse_str($accessTokenResponse, $accessTokenResponseArray);

        if(isset($accessTokenResponseArray['oauth_problem']))
        {
            throw new LaravelXeroException($accessTokenResponseArray['oauth_problem'].', '.$accessTokenResponseArray['oauth_problem_advice']);
        }


        $this->oauth_token = $accessTokenResponseArray['oauth_token'];
        $this->oauth_secret = $accessTokenResponseArray['oauth_token_secret'];
        $this->oauth_expires_in = $accessTokenResponseArray['oauth_expires_in'];
        $this->xero_org_muid = $accessTokenResponseArray['xero_org_muid'];

        $request = $this->request();
        $this->session(
            [
                'xero' => $this->getAllAttribute()
            ]
        );

        $request->session()->save();

        return $this;
    }

    public function setOAuthAttribute($request)
    {
        $this->oauth_token = $request['oauth_token'];
        $this->oauth_verifier = $request['oauth_verifier'];
        $this->org = $request['org'];

        $xeroData = ($this->session()->all())['xero'];
        $xeroData = json_decode(json_encode($xeroData)); // array turn to object

        try{

            $this->oauth_secret = $xeroData->oauth_secret;
            $this->combinedSecret = $xeroData->combinedSecret;


        }catch (\Exception $e)
        {
            throw new LaravelXeroException("Trying to get the authorized data without being authenticated");
        }

        return $this;
    }

    public static function xeroCallback($url)
    {
        $xero = new Xero();
        $request = $xero->request()->all();
        $xero->setOAuthAttribute($request)->accessToken()->redirect($url);

        return $xero;

    }

    public static function to($url,array $url_query_array = array())
    {
        $xeroRequest = new Xero();
        $response = $xeroRequest->getNormalRequestFullUrl($url,$url_query_array);
        return $response;
    }

    public function toUrl($url,array $url_query_array = array())
    {
        $response = $this->getNormalRequestFullUrl($url,$url_query_array);
        return $response;
    }

    public function getNormalRequestFullUrl($url,$url_query_array)
    {
        $this->main_endpoint_to_be_request = $url;
        //$this->getNormalRequestArray()
        $this->renewGetNonce();

        //--------------------------------------------------------start here

        if(count($url_query_array))
        {
            $this->assignXeroAttributeArray($url_query_array);
        }


        $this->parameterWithoutSignature = $this->turnArrayToUrlQuery($this->xeroAttributeArray);


        $this->combinedString = $this->turnToXeroFormatForSignatureData("GET",$this->main_endpoint_to_be_request,$this->parameterWithoutSignature);
        $this->assignSignatureToAttribute();

        $this->url_parameter = $this->appendParameterToUrlQuery($this->parameterWithoutSignature,
            [
                'oauth_signature' => $this->signature
            ]);
        $this->full_url_to_be_request = $this->turnToFullUrl($this->main_endpoint_to_be_request,$this->url_parameter);
        $this->makeCall();

        return $this;

    }

    public function renewGetNonce()
    {
        $this->xeroAttributeArray['oauth_nonce'] = $this->getNonce();
    }

    public function assignXeroAttributeArray($url_query_array)
    {
        /*foreach($url_query_array as $key => $value)
        {
            $this->xeroAttributeArray[$key] = $value;
        }*/

        $this->xeroAttributeArray = array_merge($url_query_array,$this->xeroAttributeArray);
    }

    public function makeCall()
    {
        $accessTokenResponse =
            Curl::to($this->full_url_to_be_request)
                ->withHeader( 'Accept: application/json')
                ->get();

        $this->response = json_decode($accessTokenResponse);
    }






}
