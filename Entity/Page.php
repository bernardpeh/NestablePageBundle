<?php
namespace Bpeh\NestablePageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Bpeh\NestablePageBundle\Model\PageBase;

/** @ORM\MappedSuperclass */
class Page extends PageBase
{
}

