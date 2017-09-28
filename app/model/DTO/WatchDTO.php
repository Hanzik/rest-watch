<?php

namespace App\Model\DTO;

use App;

class WatchDTO extends BaseDTO implements IDTO
{
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var int
	 */
	protected $price;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var FountainDTO
	 */
	protected $fountainDto;

	private $params;

	public function __construct(array $data = NULL, $fountainDto = NULL)
	{
		try {
			if (is_null($data)) {
				throw new App\Model\InvalidArgumentException("Watch data is missing.");
			}

			$this->id = $data['id'] ?? NULL;
			$this->title = $data['title'] ?? NULL;
			$this->price = $data['price'] ?? NULL;
			$this->description = $data['description'] ?? NULL;
			$this->fountainDto = is_null($fountainDto) ? new FountainDTO($data['fountain'] ?? NULL) : $fountainDto;

			$this->params = [
				'title'       => $this->title,
				'price'       => $this->price,
				'description' => $this->description
			];

			$this->validateObject();
		}
		catch (\Exception $e) {
			throw new App\Model\InvalidArgumentException($e->getMessage());
		}
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function getFountainDto(): FountainDTO
	{
		return $this->fountainDto;
	}

	/**
	 * We assume all three parameters are required and can not be null.
	 */
	private function validateObject()
	{
		foreach ($this->params as $parameterName => $parameterValue) {
			if (is_null($parameterValue)) {
				throw new App\Model\InvalidArgumentException("Invalid watch object. " . $parameterName . " is required.");
			}
		}

		$this->validateTitle();
		$this->validatePrice();
		$this->validateDescription();
	}

	/**
	 * Title must follow these rules:
	 *        a) Must be a non-empty string
	 *        b) Can not be longer than Watch::TITLE_MAX_LENGTH
	 *        c) Can not be shorter than Watch::TITLE_MIN_LENGTH
	 */
	private function validateTitle()
	{
		$errMsg = "Watch title must be a non-empty string with at most " . App\Model\Entity\Watch\Watch::TITLE_MAX_LENGTH . " characters.";

		if (empty($this->title)) {
			throw new App\Model\InvalidArgumentException($errMsg);
		}
		if (strlen($this->title) > App\Model\Entity\Watch\Watch::TITLE_MAX_LENGTH) {
			throw new App\Model\InvalidArgumentException($errMsg);
		}
	}

	/**
	 * Price must follow these rules:
	 *        a) Must be an integer
	 */
	private function validatePrice()
	{
		$errMsg = "Watch price must be an integer.";
		if (!ctype_digit(strval($this->price))) {
			throw new App\Model\InvalidArgumentException($errMsg);
		}
		$this->price = intval($this->price);
	}

	/**
	 * Description must follow these rules:
	 *        a) Must be a non-empty string
	 */
	private function validateDescription()
	{
		$errMsg = "Watch description must be a non-empty string.";

		if (empty($this->description)) {
			throw new App\Model\InvalidArgumentException($errMsg);
		}
	}

	public function serialize(): array
	{
		return [
			'id'          => $this->getId(),
			'title'       => $this->getTitle(),
			'price'       => $this->getPrice(),
			'description' => $this->getDescription(),
			'fountain'    => $this->getFountainDto()->serialize()
		];
	}

	public static function createFromEntity(App\Model\Entity\Watch\Watch $watch)
	{
		return new WatchDTO(
			[
				'id'          => $watch->getId(),
				'title'       => $watch->getTitle(),
				'price'       => $watch->getPrice(),
				'description' => $watch->getDescription()
			], FountainDTO::createFromEntity($watch->getFountain())
		);
	}
}
