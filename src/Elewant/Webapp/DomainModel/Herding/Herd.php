<?php

declare(strict_types=1);

namespace Elewant\Webapp\DomainModel\Herding;

/**
 * @todo Is it ok to use Herding\DomainModel here?
 */

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedCollection;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(columns={"formed_on"}), @ORM\Index(columns={"shepherd_id"})})
 * This entity has a companion proxy, therefor is not final.
 */
class Herd
{
    /**
     * @todo UserBundle:User uses mapping-type "shepherd_id", and here it's "guid". Why?
     * @ORM\Column(type="guid")
     * @var string
     */
    private $shepherdId;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $formedOn;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @var string
     */
    private $herdId;

    /**
     * @ORM\OneToMany(targetEntity="ElePHPant", mappedBy="herd", cascade={"persist"})
     * @var ArrayCollection
     */
    private $elePHPants;

    /**
     * @ORM\OneToMany(targetEntity="DesiredBreed", mappedBy="herd", cascade={"persist"})
     * @var ArrayCollection
     */
    private $desiredBreeds;

    private function __construct()
    {
        $this->elePHPants = new ArrayCollection();
    }

    public function shepherdId(): string
    {
        return $this->shepherdId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function formedOn(): DateTime
    {
        return $this->formedOn;
    }

    public function herdId(): string
    {
        return $this->herdId;
    }

    public function elePHPants(): Collection
    {
        return $this->elePHPants;
    }

    public function breeds(): BreedCollection
    {
        $collection = BreedCollection::fromArray([]);

        foreach ($this->elePHPants as $elePHPant) {
            $collection->add($elePHPant->breed());
        }

        return $collection;
    }

    public function desiredBreeds(): BreedCollection
    {
        $collection = BreedCollection::fromArray([]);

        foreach ($this->desiredBreeds as $desiredBreed) {
            $collection->add($desiredBreed->breed());
        }

        return $collection;
    }

    public function filteredByBreed(Breed $breed): Collection
    {
        return $this->elePHPants->filter(
            static function (ElePHPant $elePHPant) use ($breed): bool {
                return $elePHPant->breed()->equals($breed);
            }
        );
    }
}
