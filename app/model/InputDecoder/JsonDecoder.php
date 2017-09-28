<?php

namespace App\Model\Decoder;

use App;
use Nette;

class JsonDecoder implements IDecoder
{
	/**
	 * @param null|string $input
	 * @return array
	 */
	public function decode(?string $input): array
	{
		if (is_null($input)) {
			return [];
		}

		try {
			return Nette\Utils\Json::decode($input, Nette\Utils\Json::FORCE_ARRAY);
		}
		catch (\Exception $e) {
			throw new App\Model\InvalidArgumentException("Input not in correct format.");
		}
	}
}
