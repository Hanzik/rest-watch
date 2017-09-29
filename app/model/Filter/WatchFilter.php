<?php

namespace App\Model\Filter;

class WatchFilter implements IFilter
{
	public function filterValuesByEqual(): array
	{
		return [
			'price' => 'price',
		];
	}

	public function filterValuesByLike(): array
	{
		return [
			'title'       => 'title',
			'description' => 'description'
		];
	}
}
