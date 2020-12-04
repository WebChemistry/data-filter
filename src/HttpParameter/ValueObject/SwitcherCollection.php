<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use InvalidArgumentException;
use WebChemistry\DataFilter\ValueObject\Switcher;

class SwitcherCollection
{

	/** @var SwitcherParameter[] */
	private array $collection = [];

	/**
	 * @param Switcher[] $switchers
	 */
	public function __construct(array $switchers, string $prefix)
	{
		foreach ($switchers as $switcher) {
			$this->collection[$switcher->getId()] = new SwitcherParameter($switcher, $prefix);
		}
	}

	public function get(string $id): SwitcherParameter
	{
		if (!isset($this->collection[$id])) {
			throw new InvalidArgumentException(sprintf('Switcher %s not exists', $id));
		}

		return $this->collection[$id];
	}

	/**
	 * @return SwitcherParameter[]
	 */
	public function all(): array
	{
		return $this->collection;
	}

	public function reset(): void
	{
		foreach ($this->collection as $switcherParameter) {
			$switcherParameter->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->collection as $switcherParameter) {
			$switcherParameter->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->collection as $switcherParameter) {
			$params = $switcherParameter->saveState($params);
		}

		return $params;
	}

}
