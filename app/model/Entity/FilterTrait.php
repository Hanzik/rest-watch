<?php

namespace App\Model\Entity;

use App;
use Doctrine;

/**
 * Adds WHERE conditions to the created DQL query in a manner specified by the $filter as all parameters are to
 * be used in a different way.
 */
trait FilterTrait
{
	/**
	 * @var App\Model\Filter\IFilter
	 */
	protected $filter;

	protected function applyFilters(Doctrine\ORM\QueryBuilder &$query, string $baseEntity, array $filterData)
	{
		$this->applyEqualFilters($query, $baseEntity, $filterData);
		$this->applyLikeFilters($query, $baseEntity, $filterData);
	}

	private function applyEqualFilters(Doctrine\ORM\QueryBuilder &$query, string $baseEntity, array $filterData)
	{
		foreach ($this->filter->filterValuesByEqual() as $parameter => $entityVariableName) {
			if (!array_key_exists($parameter, $filterData)) {
				continue;
			}
			$query->andWhere($baseEntity . '.' . $entityVariableName . ' = :' . $parameter)
				  ->setParameter($parameter, $filterData[$parameter]);
		}
	}

	private function applyLikeFilters(Doctrine\ORM\QueryBuilder &$query, string $baseEntity, array $filterData)
	{
		foreach ($this->filter->filterValuesByLike() as $parameter => $entityVariableName) {
			if (!array_key_exists($parameter, $filterData)) {
				continue;
			}
			$query->andWhere($baseEntity . '.' . $entityVariableName . ' LIKE :' . $parameter)
				  ->setParameter($parameter, '%' . $filterData[$parameter] . '%');
		}
	}
}
