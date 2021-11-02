<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\Helper\HttpParameterHelper;

final class PageHttpParameter implements HttpParameterInterface
{

	private int $value = 1;

	public function setValue(int $value): void
	{
		$this->value = max($value, 1);
	}

	public function getValue(): int
	{
		return $this->value;
	}

	public function reset(): void
	{
		$this->setValue($this->value);
	}

	public function loadState(array $params): void
	{
		if (HttpParameterHelper::issetAndNumeric($params, $this->getHttpId())) {
			$this->setValue((int) $params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value !== 1) {
			$params[$this->getHttpId()] = $this->value;
		}

		return $params;
	}

	public function getHttpId(): string
	{
		return 'page';
	}

}
