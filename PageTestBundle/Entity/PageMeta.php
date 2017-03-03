<?php

namespace Bpeh\NestablePageBundle\PageTestBundle\Entity;

use Bpeh\NestablePageBundle\Model\PageMetaBase;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\Table(name="pagetest_meta")
 * @ORM\Entity(repositoryClass="Bpeh\NestablePageBundle\PageTestBundle\Repository\PageMetaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PageMeta extends PageMetaBase
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
