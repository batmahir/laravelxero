<?php

namespace Batmahir\Laravelxero;

use Ixudra\Curl\Facades\Curl;

class XeroOAuth extends Xero
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

    public function authorize($direct_redirect = true)
    {
        $this->sendGetRequestForRequestToken();

        $this->full_url_to_be_request = $this->authorization_endpoint.'?oauth_token='.$this->oauth_token.'&scope=';

        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }


        file_put_contents($this->file ,collect($this->getAllAttribute())->toJson());

        if($direct_redirect == true)
        {
            header('Location: '.$this->full_url_to_be_request);
        }

        return $this->full_url_to_be_request;

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

        return $this;
    }

    public function setOAuthAttribute($request)
    {
        $this->oauth_token = $request['oauth_token'];
        $this->oauth_verifier = $request['oauth_verifier'];
        $this->org = $request['org'];

        $xeroData = json_decode(file_get_contents($this->file));

        try{

            $this->oauth_secret = $xeroData->oauth_secret;
            $this->combinedSecret = $xeroData->combinedSecret;

        }catch (\Exception $e)
        {
            throw new LaravelXeroException("Trying to get the authorized data without being authenticated");
        }

        return $this;
    }



}
