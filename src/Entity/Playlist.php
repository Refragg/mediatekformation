<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant une playlist
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /**
     * L'identifiant de la playlist
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom de la playlist
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    /**
     * La description de la playlist
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Les formations contenues dans cette playlist
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /**
     * Constructeur de l'entité
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Retourne le nombre de formations contenues dans la playlist
     * @return int
     */
    public function getFormationsCount(): int {
        return count($this->formations);
    }

    /**
     * Ajoute une formation à la liste des formations de la playlist
     * @param Formation $formation La formation à ajouter
     * @return $this
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * Supprime une formation à la liste des formations de la playlist
     * @param Formation $formation La formation à supprimer
     * @return $this
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getPlaylist() === $this) {
                $formation->setPlaylist(null);
            }
        }

        return $this;
    }
    
    /**
     * Retourne les catégories de la playlist en prenant en compte les catégories de chaque formation
     * @return Collection<int, string>
     */
    public function getCategoriesPlaylist() : Collection
    {
        $categories = new ArrayCollection();
        foreach($this->formations as $formation){
            $categoriesFormation = $formation->getCategories();
            foreach($categoriesFormation as $categorieFormation) {
                if(!$categories->contains($categorieFormation->getName())){
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }
        
}
