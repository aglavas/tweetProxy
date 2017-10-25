<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Tweet;
use AppBundle\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TweetRepositoryTest extends KernelTestCase
{
    /**
     * @var TweetRepository
     */
    private $tweetRepository;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->tweetRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Tweet::class);
    }

    public function testFullTextSearchQueryBuilderWithBothParameters()
    {
        $result = $this->tweetRepository->fullTextSearch(
            [
                'tweetText' => 'aaa',
                'userId'    => 2,
            ]
        );

        $this->assertEquals('SELECT t, t.text, u.name, u.screenName, MATCH_AGAINST (t.text, :tweet) as score FROM AppBundle\Entity\Tweet t INNER JOIN AppBundle:User u WITH t.user = u.id WHERE MATCH_AGAINST(t.text, :tweet) > 0 AND t.user = :user_id', $result->getDQL());
    }

    public function testFullTextSearchQueryBuilderWithTweetTextParameter()
    {
        $result = $this->tweetRepository->fullTextSearch(
            [
                'tweetText' => 'aaa',
                'userId'    => null,
            ]
        );

        $this->assertEquals('SELECT t, t.text, u.name, u.screenName, MATCH_AGAINST (t.text, :tweet) as score FROM AppBundle\Entity\Tweet t INNER JOIN AppBundle:User u WITH t.user = u.id WHERE MATCH_AGAINST(t.text, :tweet) > 0', $result->getDQL());
    }


    public function testFullTextSearchQueryBuilderWithUserIdParameter()
    {
        $result = $this->tweetRepository->fullTextSearch(
            [
                'tweetText' => null,
                'userId'    => 2,
            ]
        );

        $this->assertEquals('SELECT t, t.text, u.name, u.screenName FROM AppBundle\Entity\Tweet t INNER JOIN AppBundle:User u WITH t.user = u.id WHERE t.user = :user_id', $result->getDQL());
    }
}
