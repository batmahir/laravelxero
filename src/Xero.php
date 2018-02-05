<?php

namespace Batmahir\Laravelxero;

use Batmahir\Laravelxero\Helper;

class Xero
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
    protected $signature;

    /*
     * OAuth Signature Method
     */
    protected $oauth_signature_method;

    /*
     * OAuth version
     */
    protected $oauth_version;

    /*
     * OAuth verifier
     */
    protected $oauth_verifier;


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
    }


    public function getAllAttribute()
    {
        return get_object_vars($this);
    }

    public function getRequestTokenArray()
    {
        return [
            'oauth_callback' => $this->callback,
            'oauth_consumer_key' => $this->consumer_key,
        ];
    }


    public function getAuthorizationArray()
    {
        return [
            'oauth_token' => $this->oauth_token,
            'scope' => ''
        ];
    }

    public function getAccessTokenArray()
    {
        return [
            'oauth_consumer_key' => $this->consumer_key,
            'oauth_token' => $this->oauth_token,
            'oauth_verifier'=> $this->oauth_verifier
        ];
    }

    public function getMandatoryArray()
    {
        return [
            'oauth_nonce'=> $this->getNonce(),
            'signature' => $this->signature,
            'oauth_signature_method' => $this->oauth_signature_method,
            'oauth_timestamp' => time(),
            'oauth_version' => $this->oauth_version
        ];
    }

    public function getMandatoryUrlQueryString($situation)
    {

        switch ($situation)
        {
            case "request-token":

                $this->getRequestTokenArray();
                break;

            case "authorize":

                $this->getAuthorizationArray();
                break;

            case "access-token" :

                $this->getAccessTokenArray();
                break;

            default :

                throw new LaravelXeroException("Unknown parameter passed");

        }
    }

    public function requestToken()
    {

    }

    public function authorize($direct_redirect = true)
    {

    }

}
