<?php

namespace Bpeh\NestablePageBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"pagebase" = "PageBase", "page" = "Bpeh\NestablePageBundle\Entity\Page"})
 */
abstract class PageBase
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublished", type="boolean", nullable=true)
     */
    protected $isPublished;

    /**
     * @var integer
     *
     * @ORM\Column(name="sequence", type="integer", nullable=true)
     */
    protected $sequence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;


    /**
     * @ORM\ManyToOne(targetEntity="Bpeh\NestablePageBundle\Model\PageBase", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")}
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Bpeh\NestablePageBundle\Model\PageBase", mappedBy="parent")
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    protected $children;
   
    /**
     * @ORM\OneToMany(targetEntity="Bpeh\NestablePageBundle\Model\PageMetaBase", mappedBy="page", cascade={"persist"}))
     */
    protected $pageMetas;
    
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
     * @param \Bpeh\NestablePageBundle\Model\PageBase $parent
     * @return Page
     */
    public function setParent(\Bpeh\NestablePageBundle\Model\PageBase $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Bpeh\NestablePageBundle\Model\PageBase 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Bpeh\NestablePageBundle\Model\PageBase $children
     * @return Page
     */
    public function addChild(\Bpeh\NestablePageBundle\Model\PageBase $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Bpeh\NestablePageBundle\Model\Page $children
     */
    public function removeChild(\Bpeh\NestablePageBundle\Model\PageBase $children)
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
     * @param \Bpeh\NestablePageBundle\Model\PageMetaBase $pageMetas
     * @return Page
     */
    public function addPageMeta(\Bpeh\NestablePageBundle\Model\PageMetaBase $pageMetas)
    {
        $this->pageMetas[] = $pageMetas;

        return $this;
    }

    /**
     * Remove pageMetas
     *
     * @param \Bpeh\NestablePageBundle\Model\PageMetaBase $pageMetas
     */
    public function removePageMeta(\Bpeh\NestablePageBundle\Model\PageMetaBase $pageMetas)
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
