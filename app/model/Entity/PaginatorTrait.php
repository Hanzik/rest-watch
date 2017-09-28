<?php

namespace App\Model\Entity;

use Doctrine;

trait PaginatorTrait
{
	private function setPage(Doctrine\ORM\QueryBuilder &$query, $page = NULL, $perPage = NULL)
	{
		if (is_null($page) && is_null($perPage)) {
			return;
		}

		$query->setFirstResult($page * $perPage + 1)
			  ->setMaxResults($perPage);
	}
}
