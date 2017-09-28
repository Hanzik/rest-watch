<?php

namespace App\Model\Decoder;

use App;
use Nette;

class XmlDecoder implements IDecoder
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
			// Decode data in XML format. Not implemented in this demo.
		}
		catch (\Exception $e) {
			throw new App\Model\InvalidArgumentException("Input not in correct format.");
		}
	}
}
