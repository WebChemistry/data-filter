<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\Helper\HttpParameterHelper;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;

final class LimitHttpParameter implements HttpParameterInterface
{

	private ?int $value;

	private ?int $default;

	public function __construct(DataFilterOptionsInterface $options)
	{
		$this->default = $options->getLimit();

		$this->reset();
	}

	public function getHttpId(): string
	{
		return 'limit';
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
		if (HttpParameterHelper::issetAndNumeric($params, $this->getHttpId())) {
			$this->value = max(1, (int) $params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value && $this->value !== $this->default) {
			$params[$this->getHttpId()] = $this->value;
		}

		return $params;
	}

}
