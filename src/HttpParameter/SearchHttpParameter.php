<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\ValueObject\SearchParameter;

final class SearchHttpParameter implements HttpParameterInterface
{

	private SearchParameter $value;

	public function __construct()
	{
		$this->value = new SearchParameter(null);
	}

	public function setValue(?string $value): void
	{
		$this->value = new SearchParameter($value);
	}

	public function getValue(): SearchParameter
	{
		return $this->value;
	}

	public function reset(): void
	{
		if ($this->value->isOk()) {
			$this->value = new SearchParameter(null);
		}
	}

	public function loadState(array $params): void
	{
		if (isset($params[$this->getHttpId()])) {
			$this->setValue($params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value->isOk()) {
			$params[$this->getHttpId()] = $this->value->get();
		}

		return $params;
	}

	public function getHttpId(): string
	{
		return 'search';
	}

}
