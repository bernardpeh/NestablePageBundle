<?php

namespace Songbird\NestablePageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PageMeta
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
     * @ORM\Column(name="page_title", type="string", length=255)
     */
    private $page_title;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_title", type="string", length=255)
     */
    private $menu_title;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=4)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    private $short_description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Songbird\NestablePageBundle\Entity\Page", inversedBy="pageMetas")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")}
     */
    private $page;

    /**
     * constructor
     */
    public function __construct()
    {
        // default values
        $this->locale = 'en';
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
     * Set page_title
     *
     * @param string $pageTitle
     * @return PageMeta
     */
    public function setPageTitle($pageTitle)
    {
        $this->page_title = $pageTitle;

        return $this;
    }

    /**
     * Get page_title
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->page_title;
    }

    /**
     * Set menu_title
     *
     * @param string $menuTitle
     * @return PageMeta
     */
    public function setMenuTitle($menuTitle)
    {
        $this->menu_title = $menuTitle;

        return $this;
    }

    /**
     * Get menu_title
     *
     * @return string 
     */
    public function getMenuTitle()
    {
        return $this->menu_title;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return PageMeta
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set short_description
     *
     * @param string $shortDescription
     * @return PageMeta
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;

        return $this;
    }

    /**
     * Get short_description
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return PageMeta
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set page
     *
     * @param \Songbird\NestablePageBundle\Entity\Page $page
     * @return PageMeta
     */
    public function setPage(\Songbird\NestablePageBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Songbird\NestablePageBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    
}
