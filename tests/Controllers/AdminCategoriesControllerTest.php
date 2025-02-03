<?php

namespace App\Tests\Controllers;

use App\Controller\admin\AdminCategoriesController;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCategoriesControllerTest extends WebTestCase
{
    private function loginClientAsAdmin($client) {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['id' => 1]);
        return $client->loginUser($adminUser);
    }

    public function testIndexAdminCategories() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/categories');

        $this->assertResponseIsSuccessful();
    }

    public function testSortPlaylistAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/categories/tri/ASC');
        $this->assertSelectorTextContains('h5.text-info', 'Android');
    }

    public function testSortPlaylistDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/categories/tri/DESC');
        $this->assertSelectorTextContains('h5.text-info', 'UML');
    }

    public function testRecherchePlaylist() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/categories/recherche', ['recherche' => 'C']);
        $this->assertSelectorCount(3, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'C#');
    }
}
