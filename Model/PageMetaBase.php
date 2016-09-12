<?php

namespace Bpeh\NestablePageBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"pagemetabase" = "PageMetaBase", "pagemeta" = "Bpeh\NestablePageBundle\Entity\PageMeta"})
 */
abstract class PageMetaBase
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
     * @ORM\Column(name="page_title", type="string", length=255)
     */
    protected $page_title;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_title", type="string", length=255)
     */
    protected $menu_title;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=4)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    protected $short_description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Bpeh\NestablePageBundle\Model\PageBase", inversedBy="pageMetas")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)}
     */
    protected $page;

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
     * @param \Bpeh\NestablePageBundle\Model\PageBase $page
     * @return PageMeta
     */
    public function setPage(\Bpeh\NestablePageBundle\Model\PageBase $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Bpeh\NestablePageBundle\Model\PageBase
     */
    public function getPage()
    {
    	return $this->page;
    }
}
