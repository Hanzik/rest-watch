<?php

namespace App\Model\DTO;

abstract class BaseDTO
{
	/**
	 * @var int
	 */
	protected $id;

	public function getId(): ?int
	{
		return $this->id;
	}
}
