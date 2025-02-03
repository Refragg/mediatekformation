<?php

namespace App\Tests\Controllers;

use App\Controller\admin\AdminFormationsController;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminFormationsControllerTest extends WebTestCase
{
    private function loginClientAsAdmin($client) {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['id' => 1]);
        return $client->loginUser($adminUser);
    }

    public function testIndexAdminFormations() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations');

        $this->assertResponseIsSuccessful();
    }

    public function testLienEditionFormationFonctionne() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations');
        $client->clickLink('Editer');
        $response = $client->getResponse()->getContent();
        self::assertStringContainsString('value="Eclipse n°8 : Déploiement"', $response);
    }

    public function testSortTitreAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/title/ASC');
        $this->assertSelectorTextSame('h5.text-info', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    public function testSortTitreDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/title/DESC');
        $this->assertSelectorTextSame('h5.text-info', 'UML : Diagramme de paquetages');
    }

    public function testRechercheTitre() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/formations/recherche/title', ['recherche' => 'test']);
        $this->assertSelectorCount(5, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Eclipse n°7 : Tests unitaires');
    }

    public function testSortPlaylistAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('td.text-left', 'Bases de la programmation (C#)');
    }

    public function testSortPlaylistDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('td.text-left', 'Visual Studio 2019 et C#');
    }

    public function testRecherchePlaylist() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/formations/recherche/name/playlist', ['recherche' => '2019']);
        $this->assertSelectorCount(15, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'C# : ListBox en couleur');
    }

    public function testSortDateAscendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('td.text-center', '25/09/2016');
    }

    public function testSortDateDescendant() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('GET', '/admin/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('td.text-center', '04/01/2021');
    }

    public function testCategorie() {
        $client = self::createClient();
        $this->loginClientAsAdmin($client);
        $client->request('POST', '/admin/formations/recherche/id/categories', ['recherche' => '4']);
        $this->assertSelectorCount(18, 'h5.text-info');
        $this->assertSelectorTextSame('h5.text-info', 'Python n°18 : Décorateur singleton');
    }
}
