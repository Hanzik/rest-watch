<?php

namespace App\Model\Entity\Fountain;

use App;
use App\Model\Entity;
use Doctrine;
use Doctrine\ORM\Mapping as ORM;
use Kdyby;

class Fountain
{
	use Kdyby\Doctrine\Entities\Attributes\Identifier;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="image_base64", type="text", length=65535, nullable=true)
	 */
	private $imageBase64;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="color", type="string", length=32, nullable=true)
	 */
	private $color;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="height", type="string", length=32, nullable=true)
	 */
	private $height;
	/**
	 * @var Entity\Watch\Watch
	 *
	 * @ORM\ManyToOne(targetEntity=Entity\Watch\Watch::class, inversedBy="fountains")
	 * @ORM\JoinColumn(name="watch_id", referencedColumnName="id")
	 */
	private $watch;

	/**
	 * @param string $imageBase64
	 * @param string $color
	 * @param string $height
	 */
	public function __construct(?string $imageBase64, ?string $color, ?string $height)
	{
		$this->imageBase64 = $imageBase64;
		$this->color = $color;
		$this->height = $height;
	}

	/**
	 * @return string
	 */
	public function getImageBase64(): ?string
	{
		return $this->imageBase64;
	}

	/**
	 * @return string
	 */
	public function getColor(): ?string
	{
		return $this->color;
	}

	/**
	 * @return string
	 */
	public function getHeight(): ?string
	{
		return $this->height;
	}

	/**
	 * @return Entity\Watch\Watch
	 */
	public function getWatch(): Entity\Watch\Watch
	{
		return $this->watch;
	}

	/**
	 * @param string $imageBase64
	 *
	 * @return $this
	 */
	public function setImageBase64(string $imageBase64): Fountain
	{
		$this->imageBase64 = $imageBase64;

		return $this;
	}

	/**
	 * @param string $color
	 *
	 * @return $this
	 */
	public function setColor(string $color): Fountain
	{
		$this->color = $color;

		return $this;
	}

	/**
	 * @param string $height
	 *
	 * @return $this
	 */
	public function setHeight(string $height): Fountain
	{
		$this->height = $height;

		return $this;
	}

	/**
	 * @param Entity\Watch\Watch $watch
	 *
	 * @return $this
	 */
	public function setWatch(Entity\Watch\Watch $watch): Fountain
	{
		$this->watch = $watch;

		return $this;
	}
}
