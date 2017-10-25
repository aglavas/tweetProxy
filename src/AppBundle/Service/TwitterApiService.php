<?php
namespace AppBundle\Service;

use AppBundle\Utils\TweeterProxyApi;

/**
 * Class TwitterApiService
 * Twitter API package as service
 *
 * @package AppBundle\Service
 */
class TwitterApiService
{
    public $api;

    /**
     * Construct using parameters from config
     *
     * TwitterApiService constructor.
     * @param $consumer_key
     * @param $consumer_secret
     * @param $access_token
     * @param $access_token_secret
     */
    public function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret)
    {
        $this->api = new TweeterProxyApi($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    }
}
