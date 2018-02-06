<?php

namespace Batmahir\Laravelxero;

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

        $request_token_url = $this->turnToFullUrl($this->request_token_endpoint,$this->url_parameter);

        return $request_token_url;

    }

}
