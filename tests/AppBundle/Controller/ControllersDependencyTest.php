<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllersDependencyTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('a')->count());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $username_uri = $crawler->filter('a')->attr('href');

        return $username_uri;
    }


    /**
     * @depends testIndex
     */
    public function testUsername($username_uri)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $username_uri);

        $this->assertGreaterThan(0, $crawler->filter('li')->count());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $text = $crawler->filter('li')->text();

        $words = str_word_count($text, 1);

        foreach ($words as $word) {
            //Full text search supports words longer than 3 characters, possible to override in php.ini
            //https://stackoverflow.com/questions/1585611/mysql-full-text-search-for-words-with-three-or-less-letters
            if (strlen($word) > 3) {
                return $word;
            }
        }

        return $words[0];
    }


    /**
     * @depends testUsername
     */
    public function testSearch($tweet_text)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'search');

        $form = $crawler->filter('form')->form();

        $form->setValues(array(
            "tweet[text]" => $tweet_text,
            "tweet[userId]"  => ""
        ));

        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('div#results')->count());
    }
}
