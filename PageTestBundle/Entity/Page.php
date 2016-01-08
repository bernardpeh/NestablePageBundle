<?php

namespace Bpeh\NestablePageBundle\PageTestBundle\Entity;

use Bpeh\NestablePageBundle\Entity\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\Table(name="pagetest")
 * @ORM\Entity(repositoryClass="Bpeh\NestablePageBundle\PageTestBundle\Entity\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Page extends BasePage
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
     * @ORM\Column(name="test_hidden", type="string", length=255, nullable=true)
     */
    protected $test_hidden;

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
     * Set test_field
     *
     * @param string $slug
     * @return Page
     */
    public function setTestHidden($test_hidden)
    {
        $this->test_hidden = $test_hidden;

        return $this;
    }

    /**
     * Get test_field
     *
     * @return string 
     */
    public function getTestHidden()
    {
        return $this->test_hidden;
    }

}
