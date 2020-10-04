<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\State;

use Psr\EventDispatcher\StoppableEventInterface;

final class StateChangedEvent implements StoppableEventInterface
{

	public const PAGINATE = 0x0001;
	public const SEARCH = 0x0010;
	public const ORDER_BY = 0x0100;
	public const RESET = 0x1000;
	public const ALL = 0x1111;

	private int $type;

	private bool $propagationStopped = false;

	public function __construct(int $type)
	{
		$this->type = $type;
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
