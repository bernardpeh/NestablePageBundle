<?php

namespace Bpeh\NestablePageBundle\PageTestBundle\Entity;

use Bpeh\NestablePageBundle\Entity\PageMeta as BasePageMeta;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\Table(name="pagetest_meta")
 * @ORM\Entity
 */
class PageMeta extends BasePageMeta
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
