<?php

namespace App\Model\Entity;

use Doctrine;

/**
 * Provides functions for simple database pagination
 *
 * @package App\Model\Entity
 */
trait PaginationTrait
{
	/**
	 * Sets $query to specified page of a given size. Should any of the parameters be null,
	 * no page will be set.
	 *
	 * @param Doctrine\ORM\QueryBuilder $query
	 * @param int|null                  $page
	 * @param int|null                  $perPage
	 */
	private function setPage(Doctrine\ORM\QueryBuilder &$query, ?int $page, ?int $perPage)
	{
		if (is_null($page) && is_null($perPage)) {
			return;
		}

		$query->setFirstResult($page * $perPage)
			  ->setMaxResults($perPage);
	}
}
