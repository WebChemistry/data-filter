<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use WebChemistry\DataFilter\HttpParameter\HttpParameterInterface;
use WebChemistry\DataFilter\ValueObject\Link;

final class LinkParameter
{

	private Link $link;

	private $value;

	private string $prefix;

	public function __construct(Link $link, string $prefix)
	{
		$this->link = $link;
		$this->prefix = $prefix;

		$this->reset();
	}

	public function getHttpId(): string
	{
		return $this->prefix . $this->link->getId();
	}

	public function getLink(): Link
	{
		return $this->link;
	}

	public function reset(): void
	{
		$this->value = $this->link->getDefault();
	}

	public function setValue($value): void
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function loadState(array $params): void
	{
		if (isset($params[$this->getHttpId()])) {
			$this->setValue($params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if (!$this->isDefault()) {
			$params[$this->getHttpId()] = $this->value;
 		}

		return $params;
	}

	public function isDefault(): bool
	{
		return $this->value === $this->link->getDefault();
	}

}
