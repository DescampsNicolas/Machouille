<?php

namespace Machouille\Site\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeChewing
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machouille\Site\Bundle\Entity\TypeChewingRepository")
 */
class TypeChewing
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="ChewingGum", mappedBy="type", cascade={"remove","persist"})
     * 
     */
    protected $chewing;
    
    
    public function __construct(){
    	$this-> chewing = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return TypeChewing
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TypeChewing
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    

    /**
     * Add chewing
     *
     * @param \Machouille\Site\Bundle\Entity\TypeChewing $chewing
     * @return TypeChewing
     */
    public function addChewing(\Machouille\Site\Bundle\Entity\TypeChewing $chewing)
    {
        $this->chewing[] = $chewing;

        return $this;
    }

    /**
     * Remove chewing
     *
     * @param \Machouille\Site\Bundle\Entity\TypeChewing $chewing
     */
    public function removeChewing(\Machouille\Site\Bundle\Entity\TypeChewing $chewing)
    {
        $this->chewing->removeElement($chewing);
    }

    /**
     * Get chewing
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChewing()
    {
        return $this->chewing;
    }
    
    public function setChewing(\Doctrine\Common\Collections\Collection $chewing){
    	$this->chewing = $chewing;
    }
}
