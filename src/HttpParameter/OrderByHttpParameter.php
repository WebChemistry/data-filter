<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use LogicException;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;
use WebChemistry\DataFilter\ValueObject\OrderBy;

final class OrderByHttpParameter implements HttpParameterInterface
{

	private ?OrderBy $value;

	private ?OrderBy $default;

	/** @var OrderBy[] */
	private array $list;

	public function __construct(DataFilterOptionsInterface $options)
	{
		$this->default = $options->getDefaultOrderBy();
		$this->list = $options->getOrderByList();

		$this->reset();
	}

	public function getHttpId(): string
	{
		return 'order';
	}

	public function isActive(OrderBy $orderBy): bool
	{
		return $this->getValue() === $orderBy;
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

	public function getRequiredValue(): OrderBy
	{
		return $this->getValue() ?? throw new LogicException('Order by is not set.');
	}

	public function reset(): void
	{
		$this->value = $this->default;
	}

	public function loadState(array $params): void
	{
		if (isset($params[$this->getHttpId()])) {
			$this->setHttpValue($params[$this->getHttpId()]);
		}
	}

	public function saveState(array $params): array
	{
		if ($this->value && $this->value !== $this->default) {
			$params[$this->getHttpId()] = $this->value->getId();
		}

		return $params;
	}

}
