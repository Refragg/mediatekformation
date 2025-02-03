<?php

namespace App\Tests\Controllers;

use App\Controller\PlaylistsController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase
{
    public function testIndexPlaylists() {
        $client = self::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testLienPlaylistFonctionne() {
        $client = self::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('Voir détail');
        $response = $client->getResponse()->getContent();
        self::assertStringContainsString('<h4 class="text-info mt-5">Bases de la programmation (C#)</h4>', $response);
    }

    public function testSortPlaylistAscendant() {
        $client = self::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5.text-info', 'Bases de la programmation (C#)');
    }

    public function testSortPlaylistDescendant() {
        $client = self::createClient();
        $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5.text-info', 'Visual Studio 2019 et C#');
    }

    public function testRecherchePlaylist() {
        $client = self::createClient();
        $client->request('POST', '/playlists/recherche/name', ['recherche' => 'test']);
        $this->assertSelectorCount(1, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'playlist test');
    }

    public function testSortCountAscendant() {
        $client = self::createClient();
        $client->request('GET', '/playlists/tri/count/ASC');
        $this->assertSelectorTextContains('h5.text-info', 'playlist test');
    }

    public function testSortCountDescendant() {
        $client = self::createClient();
        $client->request('GET', '/playlists/tri/count/DESC');
        $this->assertSelectorTextContains('h5.text-info', 'Bases de la programmation (C#)');
    }

    public function testCategorie() {
        $client = self::createClient();
        $client->request('POST', '/playlists/recherche/id/categories', ['recherche' => '6']);
        $this->assertSelectorCount(3, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Compléments Android (programmation mobile)');
    }
}
