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

        $this->parameterWithoutSignature = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->assignSignatureToAttribute();

        $parameter_UrlQuery = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->url_parameter = $this->appendParameterToUrlQuery($parameter_UrlQuery,['oauth_signature' => $this->signature]);

        $this->full_url_to_be_request = $this->turnToFullUrl($this->request_token_endpoint,$this->url_parameter);

        return $this;

    }

    public function sendGetRequestForRequestToken()
    {
        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }

        $requestTokenResponse = Curl::to($this->full_url_to_be_request)->get();

        parse_str($requestTokenResponse, $requestTokenResponseArray);

        $this->oauth_token = $requestTokenResponseArray['oauth_token'];
        $this->oauth_secret = $requestTokenResponseArray['oauth_token_secret'];

        return $this;
    }

    public function authorize($direct_redirect = true)
    {
        $this->full_url_to_be_request = $this->authorization_endpoint.'?oauth_token='.$this->oauth_token.'&scope=';

        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }



    }

}
