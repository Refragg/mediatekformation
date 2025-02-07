<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Classe faisant l'interface entre les données des playlists et l'application
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute ou modifie une playlist
     * @param Playlist $entity
     * @return void
     */
    public function addOrEdit(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();
    }

    /**
     * Retourne toutes les playlists triées sur le nombre de formations dans la playlist
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByCount($ordre): array{
        return $this->createQueryBuilder('p')
            ->leftjoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('COUNT(f.id)', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }
        if($table==""){
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
        }
    }
    
}
