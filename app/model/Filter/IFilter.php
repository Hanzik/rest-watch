<?php

namespace App\Model\Filter;

/**
 * Simple interface to specify parameters from requests which are to be applied to database
 * queries as filters. We want to filter some parameters by LIKE (mostly strings), some parameters
 * by equal, less than or more than (numbers, dates etc.).
 *
 * These arrays should provide mappings from request query parameters to entity parameter names so they
 * can be correctly applied in DQL queries.
 *
 * Only equal (=) and like (%%) will be supported in this demo.
 *
 * @package App\Model\Filter
 */
interface IFilter
{
	/**
	 * @return array
	 */
	public function filterValuesByEqual(): array;

	/**
	 * @return array
	 */
	public function filterValuesByLike(): array;
}
