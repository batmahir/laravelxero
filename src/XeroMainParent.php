<?php

namespace Batmahir\Laravelxero;

use Batmahir\Laravelxero\Helper;

class XeroMainParent
{

    use Helper;

    /*
     *  Callback
     */
    protected $callback;

    /*
     *  Xero consumer key
     */
    protected $consumer_key;

    /*
     *  Xero consumer secret
     */
    protected $consumer_secret;

    /*
     * Xero oauth token
     */
    protected $oauth_token;

    /*
     * Xero oauth secret
     */
    protected $oauth_secret;

    /*
     * Xero random string with 5 length
     */
    protected $nonce;

    /*
     * Xero Main Endpoint
     */
    protected $request_token_endpoint;
    protected $authorization_endpoint;
    protected $access_token_endpoint;

    /*
     * OAuth Signature Method
     */
    protected $oauth_signature_method;

    /*
     * Signature value
     */
    protected $signature;

    /*
     * OAuth version
     */
    protected $oauth_version;

    /*
     * OAuth verifier
     */
    protected $oauth_verifier;

    /*
     * Xero attribte array
     */
    protected $xeroAttributeArray;

    /*
     * Consumer secret and OAuth secret combined together urlencoded
     */
    protected $combinedSecret;

    /*
     *
     */
    protected $combinedSecretUrlDecoded;

    /*
     * Parameter of the url query string
     */
    protected $url_parameter;

    /*
     * Full url to be request
     */
    protected $full_url_to_be_request;

    /*
     * Value to be used in for generating signature together with $combinedSecret
     */
    protected $parameterWithoutSignature;

    /*
     * This parameter will have value after the authorization complete
     */
    protected $org;

    /*
     * File for storing the xero attribute data , this package not using session and etc.
     */
    protected $file;

    /*
     * Main endpoint to be request
     */
    protected $main_endpoint_to_be_request;

    /*
     * Values of combined request method with url and parameter based on Xero format
     */
    protected $combinedString;

    /*
     * Values of combined request method with url and parameter based on Xero format
     */
    protected $combinedStringNotEncoded;




    protected $time;
    /**
     * Xero constructor.
     */
    public function __construct()
    {
        $this->consumer_key = config('xerobat.consumer_key');
        $this->consumer_secret = config('xerobat.consumer_secret');

        $this->callback = config('xerobat.callback');

        $this->request_token_endpoint = config('xerobat.request_token_endpoint');
        $this->authorization_endpoint = config('xerobat.authorization_endpoint');
        $this->access_token_endpoint = config('xerobat.access_token_endpoint');

        $this->oauth_signature_method = config('xerobat.oauth_signature_method');
        $this->oauth_version =  config('xerobat.oauth_version');

        $this->combinedSecret = $this->consumer_secret.'&';

        $this->file = realpath(dirname(__FILE__)).'/../xerodata.json';

        $this->time = time();

        $this->nonce = $this->getNonce();
    }

    /**
     * Get All attribute of the class
     *
     * @return array
     */
    public function getAllAttribute()
    {
        return get_object_vars($this);
    }

    /**
     * Get array for the 'request token' endpoint
     *
     * @return $this
     */
    public function getRequestTokenArray()
    {
        $this->xeroAttributeArray['oauth_callback'] = $this->callback;
        $this->xeroAttributeArray['oauth_consumer_key'] = $this->consumer_key;

        return $this;
    }

    /**
     * Get array for the 'authorization' endpoint
     *
     * @return $this
     */
    public function getAuthorizationArray()
    {
        $this->xeroAttributeArray['oauth_token'] = $this->oauth_token;
        $this->xeroAttributeArray['scope'] = $this->oauth_token;

        return $this;
    }

    /**
     * Get array for the 'access token' endpoint
     *
     * @return $this
     */
    public function getAccessTokenArray()
    {
        $this->xeroAttributeArray['oauth_consumer_key'] = $this->consumer_key;
        $this->xeroAttributeArray['oauth_token'] = $this->oauth_token;
        $this->xeroAttributeArray['oauth_verifier'] = $this->oauth_verifier;

        return $this;
    }

    public function getNormalRequestArray()
    {
        $this->xeroAttributeArray['oauth_consumer_key'] = $this->consumer_key;
        $this->xeroAttributeArray['oauth_token'] = $this->oauth_token;

        return $this;
    }

    /**
     * Get mandatory array for all the Xero's endpoint
     *
     * @return $this
     */
    public function getMandatoryArray()
    {
        $this->xeroAttributeArray['oauth_nonce'] = $this->getNonce();
        $this->xeroAttributeArray['oauth_signature_method'] = $this->oauth_signature_method;
        $this->xeroAttributeArray['oauth_timestamp'] = $this->time;
        $this->xeroAttributeArray['oauth_version'] = $this->oauth_version;

        return $this;
    }


    public function assignSignatureToAttribute()
    {
        $combinedString = $this->turnToXeroFormatForSignatureData('GET',$this->main_endpoint_to_be_request,$this->parameterWithoutSignature);
        $this->combinedString = $combinedString;
        $this->combinedSecretUrlDecoded = urldecode($this->combinedString);
        $this->signature = $this->generateSignature($this->combinedString,$this->combinedSecret);

        return $this;
    }

}
