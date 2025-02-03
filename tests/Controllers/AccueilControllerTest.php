<?php

namespace App\Tests\Controllers;

use App\Controller\AccueilController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccueilControllerTest extends WebTestCase
{
    public function testIndex() {
        $client = self::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}
