<?php

namespace App\Model\Entity;

trait EntityTimestampTrait
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_created", type="datetime", nullable=false)
	 */
	protected $dateCreated;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_modified", type="datetime", nullable=false)
	 */
	protected $dateModified;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_removed", type="datetime", nullable=false)
	 */
	protected $dateRemoved;

	public function getDateCreated(): \DateTime
	{
		return $this->dateCreated;
	}

	public function getDateModified(): \DateTime
	{
		return $this->dateModified;
	}

	public function getDateRemoved(): \DateTime
	{
		return $this->dateRemoved;
	}

	/**
	 * @param \DateTime $dateCreated
	 *
	 * @return $this
	 */
	public function setDateCreated(\DateTime $dateCreated = NULL)
	{
		$this->dateCreated = is_null($dateCreated) ? new \DateTime : $dateCreated;
		$this->setDateModified($dateCreated);

		return $this;
	}

	/**
	 * @param \DateTime $dateModified
	 *
	 * @return $this
	 */
	public function setDateModified(\DateTime $dateModified = NULL)
	{
		$this->dateModified = is_null($dateModified) ? new \DateTime : $dateModified;

		return $this;
	}

	/**
	 * @param \DateTime $dateRemoved
	 *
	 * @return $this
	 */
	public function setDateRemoved(\DateTime $dateRemoved = NULL)
	{
		$this->dateRemoved = is_null($dateRemoved) ? new \DateTime : $dateRemoved;

		return $this;
	}
}
