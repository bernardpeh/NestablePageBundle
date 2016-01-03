<?php

namespace Songbird\NestablePageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Songbird\NestablePageBundle\Entity\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Page
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublished", type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @var integer
     *
     * @ORM\Column(name="sequence", type="integer", nullable=true)
     */
    private $sequence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")}
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $children;
   
    /**
     * @ORM\OneToMany(targetEntity="Songbird\NestablePageBundle\Entity\PageMeta", mappedBy="page", cascade={"persist"}))
     */
    private $pageMetas;
    
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
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return Page
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     * @return Page
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Page
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Page
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pageMetas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        // update the modified time
        $this->setModified(new \DateTime());

        // for newly created entries
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime('now'));
        }
        $this->created = new \DateTime();
    }

    /**
     * Set parent
     *
     * @param \Songbird\NestablePageBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Songbird\NestablePageBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Songbird\NestablePageBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Songbird\NestablePageBundle\Entity\Page $children
     * @return Page
     */
    public function addChild(\Songbird\NestablePageBundle\Entity\Page $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Songbird\NestablePageBundle\Entity\Page $children
     */
    public function removeChild(\Songbird\NestablePageBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add pageMetas
     *
     * @param \Songbird\NestablePageBundle\Entity\PageMeta $pageMetas
     * @return Page
     */
    public function addPageMeta(\Songbird\NestablePageBundle\Entity\PageMeta $pageMetas)
    {
        $this->pageMetas[] = $pageMetas;

        return $this;
    }

    /**
     * Remove pageMetas
     *
     * @param \Songbird\NestablePageBundle\Entity\PageMeta $pageMetas
     */
    public function removePageMeta(\Songbird\NestablePageBundle\Entity\PageMeta $pageMetas)
    {
        $this->pageMetas->removeElement($pageMetas);
    }

    /**
     * Get pageMetas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPageMetas()
    {
        return $this->pageMetas;
    }
    
    /**
     * convert object to string
     * @return string
     */
    public function __toString()
    {
        return $this->slug;
    }
}
