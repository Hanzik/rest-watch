<?php

namespace App\Model\DTO;

use App;

class FountainDTO extends BaseDTO implements IDTO
{
	/**
	 * @var string
	 */
	private $imageBase64;
	/**
	 * @var string
	 */
	private $color;
	/**
	 * @var string
	 */
	private $height;

	public function __construct($data = NULL)
	{
		try {
			if (is_null($data)) {
				throw new App\Model\InvalidArgumentException("Fountain data is missing.");
			}

			$this->imageBase64 = is_array($data) ? NULL : $data;
			$this->color = $data['color'] ?? NULL;
			$this->height = $data['height'] ?? NULL;

			$this->validateObject();
		}
		catch (\Exception $e) {
			throw new App\Model\InvalidArgumentException($e->getMessage());
		}
	}

	public function getImageBase64(): ?string
	{
		return $this->imageBase64;
	}

	public function getColor(): ?string
	{
		return $this->color;
	}

	public function getHeight(): ?string
	{
		return $this->height;
	}

	public function setImageBase64(string $imageBase64)
	{
		$this->imageBase64 = $imageBase64;
	}

	public function setColor(string $color)
	{
		$this->color = $color;
	}

	public function setHeight(string $height)
	{
		$this->height = $height;
	}

	/**
	 * Fountain must contain either a valid value in imageBase64 paramater or in color/height pair. Should all the
	 * parameters be non-null, imageBase64 will be preferred.
	 */
	private function validateObject()
	{
		if (!is_null($this->imageBase64) || (!is_null($this->color) && !is_null($this->height))) {
			return;
		}

		throw new App\Model\InvalidArgumentException("Fountain object is not valid.");
	}

	public function serialize()
	{
		if (!is_null($this->getImageBase64())) {
			return $this->getImageBase64();
		}

		return [
			'color'  => $this->getColor(),
			'height' => $this->getHeight()
		];
	}

	public static function createFromEntity(App\Model\Entity\Fountain\Fountain $fountain)
	{
		if (is_null($fountain->getImageBase64())) {
			return new FountainDTO(
				[
					'color'  => $fountain->getColor(),
					'height' => $fountain->getHeight()
				]
			);
		}

		return new FountainDTO($fountain->getImageBase64());
	}
}
