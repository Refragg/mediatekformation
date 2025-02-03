<?php

namespace App\Tests\Controllers;

use App\Controller\FormationsController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationsControllerTest extends WebTestCase
{
    public function testIndexFormations() {
        $client = self::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    public function testLienFormationFonctionne() {
        $client = self::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('Miniature formation');
        $response = $client->getResponse()->getContent();
        self::assertStringContainsString('<h4 class="text-info mt-5">Eclipse n°8 : Déploiement</h4>', $response);
    }

    public function testSortTitreAscendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextSame('h5.text-info', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    public function testSortTitreDescendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextSame('h5.text-info', 'UML : Diagramme de paquetages');
    }

    public function testRechercheTitre() {
        $client = self::createClient();
        $client->request('POST', '/formations/recherche/title', ['recherche' => 'test']);
        $this->assertSelectorCount(5, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Eclipse n°7 : Tests unitaires');
    }

    public function testSortPlaylistAscendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('td.text-left', 'Bases de la programmation (C#)');
    }

    public function testSortPlaylistDescendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('td.text-left', 'Visual Studio 2019 et C#');
    }

    public function testRecherchePlaylist() {
        $client = self::createClient();
        $client->request('POST', '/formations/recherche/name/playlist', ['recherche' => '2019']);
        $this->assertSelectorCount(15, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'C# : ListBox en couleur');
    }

    public function testSortDateAscendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('td.text-center', '25/09/2016');
    }

    public function testSortDateDescendant() {
        $client = self::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('td.text-center', '04/01/2021');
    }

    public function testCategorie() {
        $client = self::createClient();
        $client->request('POST', '/formations/recherche/id/categories', ['recherche' => '4']);
        $this->assertSelectorCount(18, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Python n°18 : Décorateur singleton');
    }
}
