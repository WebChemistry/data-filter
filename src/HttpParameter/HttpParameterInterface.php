<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

interface HttpParameterInterface
{

	public function getValue();

	public function reset(): void;

	public function loadState(array $params): void;

	/**
	 * @param mixed[] $params
	 * @return mixed[]
	 */
	public function saveState(array $params): array;

}
