<?php

namespace App\Tests\Repositories;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase
{
    private function getRepository() {
        self::bootKernel();
        return self::getContainer()->get(CategorieRepository::class);
    }

    public function testFindByName() {
        $repository = $this->getRepository();
        $categories = $repository->findByName("C");
        $this->assertCount(3, $categories);
    }

    public function testFindAllOrderByAscendant() {
        $repository = $this->getRepository();
        $categories = $repository->findAllOrderBy('ASC');

        $this->assertEquals('Android', $categories[0]->getName());
        $this->assertEquals('C#', $categories[1]->getName());
        $this->assertEquals('Cours', $categories[2]->getName());
    }

    public function testFindAllOrderByDescendant() {
        $repository = $this->getRepository();
        $categories = $repository->findAllOrderBy('DESC');

        $this->assertEquals('UML', $categories[0]->getName());
        $this->assertEquals('SQL', $categories[1]->getName());
        $this->assertEquals('Python', $categories[2]->getName());
    }

    public function testExistsByNameValide() {
        $repository = $this->getRepository();
        $existe = $repository->existsByName("C#");
        $this->assertTrue($existe);
    }

    public function testExistsByNameInvalide() {
        $repository = $this->getRepository();
        $existe = $repository->existsByName("C++");
        $this->assertFalse($existe);
    }
}
