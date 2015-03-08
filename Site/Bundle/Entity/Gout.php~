<?php

namespace Machouille\Site\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gout
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machouille\Site\Bundle\Entity\GoutRepository")
 */
class Gout
{
    /**
     * @var integer
     *
     * @ORM\Column(name="gout_id", type="integer")
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
     * @ORM\ManyToMany(targetEntity="ChewingGum", mappedBy="gout")
     */
	protected $chewing;
    
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
     * @return Gout
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
     * Set id
     *
     * @param integer $id
     * @return Gout
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chewing = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add chewing
     *
     * @param \Machouille\Site\Bundle\Entity\ChewinGum $chewing
     * @return Gout
     */
    public function addChewing(\Machouille\Site\Bundle\Entity\ChewinGum $chewing)
    {
        $this->chewing[] = $chewing;

        return $this;
    }

    /**
     * Remove chewing
     *
     * @param \Machouille\Site\Bundle\Entity\ChewinGum $chewing
     */
    public function removeChewing(\Machouille\Site\Bundle\Entity\ChewinGum $chewing)
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
}
