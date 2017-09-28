<?php

namespace App\Model\Decoder;

interface IDecoder
{
	/**
	 * @return array
	 */
	public function decode(?string $input): array;
}
