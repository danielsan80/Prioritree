<?php

use Dan\CleanGame\Test\WebTestCase;

class IndexTest extends WebTestCase
{
    
    public function testHomeView()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        
        $this->assertEquals('done', $crawler->filter('h1')->first()->text());
//        $this->assertCount(1, $crawler->filter('table.activities'));
//        $this->assertCount(3, $crawler->filter('table.activities tr'));
    }

}