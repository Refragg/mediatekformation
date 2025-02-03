<?php

namespace App\Tests\Repositories;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    private function getRepository() {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    public function testFindAllOrderByCountAscendant() {
        $repository = $this->getRepository();
        $playlists = $repository->findAllOrderByCount('ASC');

        $this->assertEquals('playlist test', $playlists[0]->getName());
        $this->assertEquals('Cours Informatique embarquée', $playlists[1]->getName());
        $this->assertEquals('Cours Merise/2', $playlists[2]->getName());
    }

    public function testFindAllOrderByCountDescendant() {
        $repository = $this->getRepository();
        $playlists = $repository->findAllOrderByCount('DESC');

        $this->assertEquals('Bases de la programmation (C#)', $playlists[0]->getName());
        $this->assertEquals('Programmation sous Python', $playlists[1]->getName());
        $this->assertEquals('MCD : exercices progressifs', $playlists[2]->getName());
    }
}
