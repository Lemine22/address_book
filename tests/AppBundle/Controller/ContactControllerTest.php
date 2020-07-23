<?php 
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest  extends WebTestCase {

    /**
     *
     * @test
     */
    public function testHomePage(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('My address book', $crawler->filter('.jumbotron h1')->text());
    }
}