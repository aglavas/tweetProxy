<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tweet;
use AppBundle\Form\Type\TweetType;
use AppBundle\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

const PAGINATION_PER_PAGE_PARAM = 'pagination_per_page';
const CURRENT_PAGE_QUERY_PARAM = 'page';
const TWEETS_SEARCH_TEMPLATE  = 'twitter/search.html.twig';
//View
const VIEW_FORM_VARIABLE  = 'form';
const VIEW_TWEETS_VARIABLE  = 'tweets';
const VIEW_MAX_PAGES_VARIABLE  = 'maxPages';
const VIEW_THIS_PAGE_VARIABLE  = 'thisPage';
const VIEW_QUERY_PARAMS_VARIABLE  = 'query_params';
//Format
const FORMAT_USER_ID_VARIABLE  = 'userId';
const FORMAT_TWEET_TEXT_VARIABLE  = 'tweetText';
const FORMAT_TEXT_VARIABLE  = 'text';



/**
 * Class TweetController
 * Controller used to manage Tweet entity
 * @package AppBundle\Controller
 */
class TweetController extends Controller
{
    private $em;

    /**
     * TweetController constructor.
     * Inject EntityManager into property
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     *
     * Performs full text search on Tweet and User entity
     *
     * @Route("/search", name="search")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(TweetType::class);
        $form->handleRequest($request);

        //Default values
        $pagination = [];
        $numPages = 1;
        //From config
        $limit = $this->container->getParameter(PAGINATION_PER_PAGE_PARAM);

        //Form is submited with data
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $params = $this->formatParams($formData);

            /** @var TweetRepository $tweet_repository */
            $tweet_repository = $this->em->getRepository(Tweet::class);
            $tweets = $tweet_repository->fullTextSearch($params);

            //Create pagination
            $pagination = new Paginator($tweets/*, $fetchJoinCollection = false*/);
            $pagination->getQuery()
                ->setFirstResult($limit * ($request->get(CURRENT_PAGE_QUERY_PARAM, 1) - 1)) // Offset
                ->setMaxResults($limit); // Limit
            $numPages = ceil($pagination->count() / $limit);
        }


        return $this->render(TWEETS_SEARCH_TEMPLATE, array(
            VIEW_FORM_VARIABLE => $form->createView(),
            VIEW_TWEETS_VARIABLE => $pagination,
            VIEW_MAX_PAGES_VARIABLE => $numPages,
            VIEW_THIS_PAGE_VARIABLE => $request->get(CURRENT_PAGE_QUERY_PARAM, 1),
            VIEW_QUERY_PARAMS_VARIABLE => $request->query->all(),
        ));
    }

    /**
     * Format search parameters to repository needs
     *
     * @param array $params
     * @return array
     */
    private function formatParams(array $params)
    {
        if ($params[FORMAT_USER_ID_VARIABLE] == null) {
            $user_id = null;
        } else {
            $user_id = $params[FORMAT_USER_ID_VARIABLE]->getId();
        }

        return [
            FORMAT_TWEET_TEXT_VARIABLE => $params[FORMAT_TEXT_VARIABLE],
            FORMAT_USER_ID_VARIABLE => $user_id
        ];
    }
}
