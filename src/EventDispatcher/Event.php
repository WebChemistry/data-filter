<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\EventDispatcher;

abstract class Event implements EventInterface
{

	private bool $propagationStopped = false;

	public function stopPropagation(): void
	{
		$this->propagationStopped = true;
	}

	public function isPropagationStopped(): bool
	{
		return $this->propagationStopped;
	}

}
