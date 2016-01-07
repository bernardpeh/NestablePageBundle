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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
