<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\State;

final class StateEventDispatcher
{

	/**
	 * @phpstan-var (callable(StateChangedEvent): void)[]
	 * @var callable[]
	 */
	private array $onStateChange = [];

	/**
	 * @phpstan-var (callable(StateChangedEvent): void)
	 */
	public function addEventListener(callable $callback): void
	{
		$this->onStateChange[] = $callback;
	}

	public static function createWithEventDispatcher(StateEventDispatcher $dispatcher): self
	{
		$self = new self();
		$self->addEventListener(function (StateChangedEvent $event) use ($dispatcher): void {
			$dispatcher->dispatch($event);
		});

		return $self;
	}

	public function dispatch(StateChangedEvent $event): void
	{
		foreach ($this->onStateChange as $callback) {
			$callback($event);

			if ($event->isPropagationStopped()) {
				break;
			}
		}
	}

}
