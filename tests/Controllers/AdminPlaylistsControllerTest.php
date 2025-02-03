<?php

namespace App\Tests\Controllers;

use App\Controller\admin\AdminPlaylistsController;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminPlaylistsControllerTest extends WebTestCase
{
    private function loginClientAsAdmin($client) {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['id' => 1]);
        return $client->loginUser($adminUser);
    }

    public function testIndexAdminPlaylists() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists');

        $this->assertResponseIsSuccessful();
    }

    public function testLienEditionPlaylistFonctionne() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists');
        $client->clickLink('Editer');
        $response = $client->getResponse()->getContent();
        self::assertStringContainsString('value="Bases de la programmation (C#)"', $response);
    }

    public function testSortPlaylistAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5.text-info', 'Bases de la programmation (C#)');
    }

    public function testSortPlaylistDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5.text-info', 'Visual Studio 2019 et C#');
    }

    public function testRecherchePlaylist() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/playlists/recherche/name', ['recherche' => 'test']);
        $this->assertSelectorCount(1, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'playlist test');
    }

    public function testSortCountAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists/tri/count/ASC');
        $this->assertSelectorTextContains('h5.text-info', 'playlist test');
    }

    public function testSortCountDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/playlists/tri/count/DESC');
        $this->assertSelectorTextContains('h5.text-info', 'Bases de la programmation (C#)');
    }

    public function testCategorie() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/playlists/recherche/id/categories', ['recherche' => '6']);
        $this->assertSelectorCount(3, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Compléments Android (programmation mobile)');
    }
}
