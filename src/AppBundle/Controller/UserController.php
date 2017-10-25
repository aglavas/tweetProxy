<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tweet;
use AppBundle\Entity\User;
use AppBundle\Repository\TweetRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\TwitterApiService;
use AppBundle\Utils\TweeterProxyApi;
use Buzz\Message\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

const ERROR_TEMPLATE = 'twitter/error.html.twig';
const USER_LIST_TEMPLATE = 'users/list.html.twig';
const TWEETS_TEMPLATE = 'twitter/tweets.html.twig';
const ERROR_MESSAGE_KEY = 'error_message';
const USERS_KEY = 'users';
const SCREEN_NAME_DQL_KEY = 'screenName';
const TWEET_COUNT_PARAM = 'tweet_count';
//Api
const SCREEN_NAME_API_PARAM = 'screen_name';
const COUNT_API_PARAM = 'count';
//View
const VIEW_USERNAME_API_PARAM = 'username';
const VIEW_TWEETS_PARAM = 'tweets';

/**
 * Class UserController
 * Controller used to manage User entity
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    private $em;

    /**
     * UserController constructor.
     * Inject EntityManager into property
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Connects to Twitter API and lists followed users
     *
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @param TwitterApiService $twitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, TwitterApiService $twitter)
    {
        /** @var Response $response */
        $response = $twitter->api->getUsers();

        if ($response->getStatusCode() != 200) {
            return $this->render(ERROR_TEMPLATE, [
                ERROR_MESSAGE_KEY => TweeterProxyApi::formatError($response)
            ]);
        }

        return $this->render(USER_LIST_TEMPLATE, [
            USERS_KEY => TweeterProxyApi::formatUsers($response)
        ]);
    }

    /**
     * Fetches user from Twitter API and saves it in local database
     *
     * @param TwitterApiService $twitter
     * @param $username
     * @param UserRepository $user_repository
     * @return User
     */

    private function fetchUser(TwitterApiService $twitter, $username, UserRepository $user_repository)
    {
        $user_response = $twitter->api->getUserByScreenName([SCREEN_NAME_API_PARAM => $username]);

        if ($user_response->getStatusCode() != 200) {
            throw new NotFoundResourceException(TweeterProxyApi::formatError($user_response));
        }

        $user_api_object = TweeterProxyApi::getContent($user_response);

        /** @var UserRepository $user_repository */
        return $user_repository->saveUser($user_api_object);
    }


    /**
     *
     * Searches for user using username on Twitter API and it's tweets. Saves tweets and user
     * information in local database.
     *
     * @Route("/{username}", name="username", requirements={"username" = "^((?!search).)*$"})
     * @param Request $request
     * @param $username
     * @param TwitterApiService $twitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usernameAction(Request $request, $username, TwitterApiService $twitter)
    {
        /** @var UserRepository $user_repository */
        $user_repository = $this->em->getRepository(User::class);
        /** @var User $user_object */
        $user_object = $user_repository->findOneBy([SCREEN_NAME_DQL_KEY => $username]);

        if (empty($user_object)) {
            $user_object = $this->fetchUser($twitter, $username, $user_repository);
        }

        $tweet_count = $this->container->getParameter(TWEET_COUNT_PARAM);

        $response = $twitter->api->getUserTweets([SCREEN_NAME_API_PARAM => $username, COUNT_API_PARAM => $tweet_count]);

        $tweet_array = TweeterProxyApi::getContent($response);

        /** @var TweetRepository $tweet_repository */
        $tweet_repository = $this->em->getRepository(Tweet::class);

        $tweet_repository->saveTweets($tweet_array, $user_object);

        // replace this example code with whatever you need
        return $this->render(TWEETS_TEMPLATE, [
            VIEW_USERNAME_API_PARAM  => $username,
            VIEW_TWEETS_PARAM    => TweeterProxyApi::getContent($response),
        ]);
    }
}
