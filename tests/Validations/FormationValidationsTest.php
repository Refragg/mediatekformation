<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase
{
    private function getFormation() {
        $formation = new Formation();
        $formation->setTitle("Formation de test");
        $formation->setDescription("Description de test");
        $formation->setVideoId("rUnuYTjaBoU");
        return $formation;
    }

    public function testPublishedAtPosterieurAMaintenant() {
        $datePosterieure = (new \DateTime('now'))->add(new \DateInterval('PT2H'));
        $formation = $this->getFormation()->setPublishedAt($datePosterieure);

        self::bootKernel();
        $validateur = self::getContainer()->get(ValidatorInterface::class);
        $erreurs = $validateur->validate($formation);
        $this->assertCount(1, $erreurs);
    }

    public function testPublishedAtAnterieurAMaintenant() {
        $dateAnterieure = (new \DateTime('now'))->sub(new \DateInterval('PT2H'));
        $formation = $this->getFormation()->setPublishedAt($dateAnterieure);

        self::bootKernel();
        $validateur = self::getContainer()->get(ValidatorInterface::class);
        $erreurs = $validateur->validate($formation);
        $this->assertCount(0, $erreurs);
    }

    public function testPublishedAtEgalAMaintenant() {
        $dateMaintenant = (new \DateTime('now'));
        $formation = $this->getFormation()->setPublishedAt($dateMaintenant);

        self::bootKernel();
        $validateur = self::getContainer()->get(ValidatorInterface::class);
        $erreurs = $validateur->validate($formation);
        $this->assertCount(0, $erreurs);
    }
}
