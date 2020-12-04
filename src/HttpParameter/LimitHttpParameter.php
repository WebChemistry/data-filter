<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\Helper\HttpParameterHelper;

final class LimitHttpParameter implements HttpParameterInterface
{

	private ?int $value;

	private ?int $default;

	public function __construct(?int $default)
	{
		$this->default = $default;

		$this->reset();
	}

	public function getValue(): ?int
	{
		return $this->value;
	}

	public function reset(): void
	{
		$this->value = $this->default;
	}

	public function loadState(array $params): void
	{
		if (HttpParameterHelper::issetAndNumeric($params, 'limit')) {
			$this->value = max(1, (int) $params['limit']);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value && $this->value !== $this->default) {
			$params['limit'] = $this->value;
		}

		return $params;
	}

}
