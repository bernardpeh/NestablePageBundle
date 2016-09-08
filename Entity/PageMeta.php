<?php
namespace Bpeh\NestablePageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Bpeh\NestablePageBundle\Model\PageMetaBase;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="pagemeta")
 * @ORM\Entity(repositoryClass="Bpeh\NestablePageBundle\Entity\PageMetaRepository")
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

