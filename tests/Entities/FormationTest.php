<?php

namespace App\Tests\Entities;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtStringDateValide() {
        $date = new \DateTime('2025-01-04 17:00:12');

        $formation = new Formation();
        $formation->setPublishedAt($date);

        $this->assertEquals('04/01/2025', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringDateVide() {
        $formation = new Formation();

        $this->assertEquals('', $formation->getPublishedAtString());
    }
}
