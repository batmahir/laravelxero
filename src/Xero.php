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
    protected $oauth_signature_method;

    /*
     * OAuth version
     */
    protected $oauth_version;


    /**
     * Xero constructor.
     */
    public function __construct()
    {
        $this->consumer_key = '';
        $this->consumer_secret = '';
    }


    public function getAllAttribute()
    {
        return get_object_vars($this);
    }

    public function getRequestTokenArray()
    {
        return [
            'oauth_callback' => '',
            'oauth_consumer_key' => '',
        ];
    }


    public function getAuthorizationArray()
    {
        return [
            'oauth_token' => '',
            'scope' => ''
        ];
    }

    public function getAccessTokenArray()
    {
        return [
            'oauth_consumer_key' => '',
            'oauth_token' => '',
            'oauth_verifier'=> ''
        ];
    }

    public function getMandatoryArray()
    {
        return [
            'oauth_nonce'=> '',
            'oauth_signature_method' => '',
            'oauth_timestamp' => '',
            'oauth_version' => ''
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
