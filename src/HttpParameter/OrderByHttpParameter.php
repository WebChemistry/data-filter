<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\ValueObject\OrderBy;

final class OrderByHttpParameter implements HttpParameterInterface
{

	private ?OrderBy $value;

	private ?OrderBy $default;

	/** @var OrderBy[] */
	private array $list;

	/**
	 * @param OrderBy[] $list
	 */
	public function __construct(?OrderBy $default, array $list)
	{
		$this->default = $default;
		$this->list = $list;

		$this->reset();
	}

	public function setValue(?OrderBy $orderBy): void
	{
		$this->value = $orderBy;
	}

	public function setHttpValue(string $value): void
	{
		if (isset($this->list[$value])) {
			$this->setValue($this->list[$value]);
		}
	}

	public function getValue(): ?OrderBy
	{
		return $this->value;
	}

	public function reset(): void
	{
		$this->value = $this->default;
	}

	public function loadState(array $params): void
	{
		if (isset($params['order'])) {
			$this->setHttpValue($params['order']);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value && $this->value !== $this->default) {
			$params['order'] = $this->value->getId();
		}

		return $params;
	}

	public function getHttpId(): string
	{
		return 'order';
	}

}
