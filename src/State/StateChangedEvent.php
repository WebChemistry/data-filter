<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\State;

use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\EventDispatcher\EventInterface;

final class StateChangedEvent implements EventInterface
{

	public const PAGINATE = 0x0000001;
	public const SEARCH = 0x0000010;
	public const ORDER_BY = 0x0000100;
	public const RESET = 0x0001000;
	public const SWITCHER = 0x0010000;
	public const FORM = 0x0100000;
	public const LINK = 0x1000000;
	public const ALL = 0x1111111;

	private int $type;

	private bool $propagationStopped = false;

	private DataFilter $dataFilter;

	public function __construct(int $type, DataFilter $dataFilter)
	{
		$this->type = $type;
		$this->dataFilter = $dataFilter;
	}

	public function getDataFilter(): DataFilter
	{
		return $this->dataFilter;
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function stopPropagation(): void
	{
		$this->propagationStopped = true;
	}

	public function isPropagationStopped(): bool
	{
		return $this->propagationStopped;
	}

}
