<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\EventDispatcher;

final class EventDispatcher
{

	/** @var mixed[] */
	private array $listeners = [];

	public function addEventListener(?string $className, callable $callback): void
	{
		$this->listeners[$className][] = $callback;
	}

	public function dispatch(EventInterface $object): void
	{
		foreach ($this->listeners[get_class($object)] ?? [] as $callback) {
			$callback($object);

			if ($object->isPropagationStopped()) {
				break;
			}
		}

		if (!$object->isPropagationStopped()) {
			foreach ($this->listeners[null] ?? [] as $callback) {
				$callback($object);

				if ($object->isPropagationStopped()) {
					break;
				}
			}
		}
	}

}
