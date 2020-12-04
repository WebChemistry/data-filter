<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\Helper;

class HttpParameterHelper
{

	public static function issetAndNumeric(array $params, string $name): bool
	{
		return isset($params[$name]) && is_numeric($params[$name]);
	}

}
