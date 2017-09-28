<?php

namespace App\Model\Entity\Watch;

use App;
use App\Model\Entity;
use Doctrine;
use Doctrine\ORM\Mapping as ORM;
use Kdyby;

/**
 * @ORM\Table(name="watches")
 * @ORM\Entity
 */
class Watch
{
	const TITLE_MAX_LENGTH = 256;

	use Kdyby\Doctrine\Entities\Attributes\Identifier;
	use Entity\EntityTimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="text", length=256, nullable=false)
	 */
	private $title;
	/**
	 * @var int
	 *
	 * @ORM\Column(name="price", type="integer", nullable=false)
	 */
	private $price;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", length=1024, nullable=false)
	 */
	private $description;
	/**
	 * @var Entity\Fountain\Fountain[]
	 *
	 * @ORM\OneToMany(targetEntity=Entity\Fountain\Fountain::class, mappedBy="watch")
	 */
	private $fountains;

	/**
	 * @param string                   $title
	 * @param int                      $price
	 * @param string                   $description
	 * @param Entity\Fountain\Fountain $fountain
	 */
	public function __construct(string $title, int $price, string $description, Entity\Fountain\Fountain $fountain)
	{
		$this->setDateCreated();

		$this->title = $title;
		$this->price = $price;
		$this->description = $description;

		$this->fountains = new Doctrine\Common\Collections\ArrayCollection;

		$this->setFountain($fountain);
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getFountain(): Entity\Fountain\Fountain
	{
		if ($this->fountains->isEmpty()) {
			return NULL;
		}

		return $this->fountains->first();
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getPrice(): int
	{
		return $this->price;
	}

	public function setTitle(string $title): string
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @param int $price
	 *
	 * @return $this
	 */
	public function setPrice(int $price): Watch
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription(string $description): Watch
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @param Entity\Fountain\Fountain $fountain
	 *
	 * @return $this
	 */
	public function setFountain(Entity\Fountain\Fountain $fountain): Watch
	{
		$this->fountains->clear();
		$this->fountains->add($fountain);
		$fountain->setWatch($this);

		return $this;
	}
}
