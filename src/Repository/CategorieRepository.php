<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Classe faisant l'interface entre les données des catégories et l'application
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
    }

    /**
     * Retourne toutes les catégories triées sur le nom
     * @param string $ordre 'ASC' ou 'DESC'
     * @return Categorie[]
     */
    public function findAllOrderBy(string $ordre): array{
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Enregistrements dont le nom contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param string $valeur
     * @return Categorie[]
     */
    public function findByName(string $valeur): array{
        if ($valeur == "") {
            return $this->findAll();
        }

        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :valeur')
            ->orderBy('c.name', 'ASC')
            ->setParameter('valeur', '%'.$valeur.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une catégorie existe en fonction de son nom
     * @param string $valeur Le nom de la catégorie à vérifier
     * @return bool
     */
    public function existsByName(string $valeur): bool{
        $categorie = $this->createQueryBuilder('c')
            ->where('c.name = :valeur')
            ->setParameter('valeur', $valeur)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return count($categorie) != 0;
    }
}
