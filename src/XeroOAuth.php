<?php

namespace Batmahir\Laravelxero;

use Ixudra\Curl\Facades\Curl;
use Batmahir\Laravelxero\FileHelper;

class XeroOAuth extends Xero
{

    use FileHelper;

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
        $this->requestToken();

        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }

        $requestTokenResponse = Curl::to($this->full_url_to_be_request)->get();

        parse_str($requestTokenResponse, $requestTokenResponseArray);

        $this->oauth_token = $requestTokenResponseArray['oauth_token'];
        $this->oauth_secret = $requestTokenResponseArray['oauth_token_secret'];
        $this->combinedSecret = $this->combinedSecret.$this->oauth_secret;

        return $this;
    }

    public function authorize($direct_redirect = true)
    {
        $this->requestToken()->sendGetRequestForRequestToken();

        $this->full_url_to_be_request = $this->authorization_endpoint.'?oauth_token='.$this->oauth_token.'&scope=';

        if(!isset($this->full_url_to_be_request))
        {
            throw new LaravelXeroException("No endpoint is set");
        }

        $this->getCurrentPath();
        file_put_contents("../xerodata.txt",collect($this->getAllAttribute())->toJson());

        if($direct_redirect == true)
        {
            header('Location: '.$this->full_url_to_be_request);
        }

        return $this->full_url_to_be_request;

    }

    public function accessToken()
    {
        $this->parameterWithoutSignature = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->assignSignatureToAttribute();

        $parameter_UrlQuery = $this->turnArrayToUrlQuery($this->xeroAttributeArray);
        $this->url_parameter = $this->appendParameterToUrlQuery($parameter_UrlQuery,['oauth_signature' => $this->signature]);

        $this->full_url_to_be_request = $this->turnToFullUrl($this->request_token_endpoint,$this->url_parameter);

        return $this;
    }

    public function setOAuthAttribute($request)
    {
        $this->oauth_token = $request['oauth_token'];
        $this->oauth_verifier = $request['oauth_verifier'];
        $this->org = $request['org'];

        return $this;
    }



}
