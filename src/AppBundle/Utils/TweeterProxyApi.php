<?php

namespace AppBundle\Utils;

use Buzz\Message\Response;
use Endroid\Twitter\Twitter;

const GET_USERS = '/friends/list';
const GET_USER_TWEETS = '/statuses/user_timeline';
const GET_USER_SCREEN_NAME = '/users/show';
const METHOD = 'GET';
const FORMAT = 'json';
const COUNT_PARAMETER = 'count';
const COUNT_MAX = 200;

/**
 * Class TweeterProxyApi
 *
 * Wrapper around Twitter API package
 *
 * @package AppBundle\Utils
 */
class TweeterProxyApi extends Twitter
{
    public function __construct(
        $consumerKey,
        $consumerSecret,
        $accessToken = null,
        $accessTokenSecret = null,
        $apiUrl = null,
        $proxy = null,
        $timeout = null,
        $verifyPeer = true
    ) {
        parent::__construct(
            $consumerKey,
            $consumerSecret,
            $accessToken,
            $accessTokenSecret,
            $apiUrl,
            $proxy,
            $timeout,
            $verifyPeer
        );
    }

    /**
     * Get twitter user friends
     *
     * @return Response
     */
    public function getUsers()
    {
        return $this->query(GET_USERS, METHOD, FORMAT, [COUNT_PARAMETER => COUNT_MAX]);
    }

    /**
     * Get single user using parameters
     *
     * @param array $params
     * @return Response
     */
    public function getUserByScreenName(array $params)
    {
        return $this->query(GET_USER_SCREEN_NAME, METHOD, FORMAT, $params);
    }

    /**
     * Get tweets from user using parameters
     *
     * @param array $params
     * @return Response
     */
    public function getUserTweets(array $params)
    {
        return $this->query(GET_USER_TWEETS, METHOD, FORMAT, $params);
    }

    /**
     * Format Twitter error response
     *
     * @param Response $response
     * @return mixed
     */
    public static function formatError(Response $response)
    {
        return json_decode($response->getContent())->errors[0]->message;
    }

    /**
     * Format Twitter users response
     *
     * @param Response $response
     * @return mixed
     */
    public static function formatUsers(Response $response)
    {
        return json_decode($response->getContent())->users;
    }

    /**
     * Get content from response
     *
     * @param Response $response
     * @return mixed
     */
    public static function getContent(Response $response)
    {
        return json_decode($response->getContent());
    }
}
