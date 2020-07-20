<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\State;

use Psr\EventDispatcher\StoppableEventInterface;

final class StateChangedEvent implements StoppableEventInterface
{

	public const PAGINATE = 'paginate';
	public const SEARCH = 'search';
	public const ORDER_BY = 'orderBy';
	public const RESET = 'reset';

	private string $type;

	private bool $propagationStopped = false;

	public function __construct(string $type)
	{
		$this->type = $type;
	}

	public function getType(): string
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
