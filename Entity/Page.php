<?php
namespace Bpeh\NestablePageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Bpeh\NestablePageBundle\Model\PageBase;

/**
 * @ORM\MappedSuperclass
 */
class Page extends PageBase
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

