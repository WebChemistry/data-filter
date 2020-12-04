<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use WebChemistry\DataFilter\ValueObject\Switcher;

final class SwitcherParameter
{

	private Switcher $switcher;

	private string $prefix;

	private bool $value;

	public function __construct(Switcher $switcher, string $prefix)
	{
		$this->switcher = $switcher;
		$this->prefix = $prefix;

		$this->reset();
	}

	public function getHttpId(): string
	{
		return $this->prefix . $this->switcher->getId();
	}

	public function getSwitcher(): Switcher
	{
		return $this->switcher;
	}

	public function reset(): void
	{
		$this->value = $this->switcher->getDefault();
	}

	public function setValue(bool $value): void
	{
		$this->value = $value;
	}

	public function setHttpValue($value): void
	{
		$this->setValue((bool) $value);
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getHttpValue(bool $nullable = true): ?bool
	{
		if ($this->isDefault() || !$nullable) {
			return !$this->value;
		}

		return null;
	}

	public function loadState(array $params): void
	{
		if (isset($params[$this->getHttpId()])) {
			$this->setHttpValue($params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if (!$this->isDefault()) {
			$params[$this->getHttpId()] = (int) $this->value;
		}

		return $params;
	}

	public function isDefault(): bool
	{
		return $this->value === $this->switcher->getDefault();
	}

}
