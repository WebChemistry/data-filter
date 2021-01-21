<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Utility;

use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use Traversable;

final class CallbackIterator implements IteratorAggregate, Countable
{

	/** @var callable */
	private $callback;

	/** @var mixed[] */
	private iterable $data;

	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function count(): int
	{
		$data = $this->getData();

		return $data instanceof Traversable ? iterator_count($data) : count($data);
	}

	public function getIterator(): iterable
	{
		$data = $this->getData();

		return $data instanceof Traversable ? $data : new ArrayIterator($data);
	}

	private function getData(): iterable
	{
		return $this->data ??= ($this->callback)();
	}

}
