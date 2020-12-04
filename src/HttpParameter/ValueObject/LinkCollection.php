<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use InvalidArgumentException;
use WebChemistry\DataFilter\ValueObject\Link;

final class LinkCollection
{

	/** @var LinkParameter[] */
	private array $collection = [];

	/**
	 * @param Link[] $links
	 */
	public function __construct(array $links, string $prefix)
	{
		foreach ($links as $link) {
			$this->collection[$link->getId()] = new LinkParameter($link, $prefix);
		}
	}

	public function get(string $id): LinkParameter
	{
		if (!isset($this->collection[$id])) {
			throw new InvalidArgumentException(sprintf('Link %s not exists', $id));
		}

		return $this->collection[$id];
	}

	/**
	 * @return LinkParameter[]
	 */
	public function all(): array
	{
		return $this->collection;
	}

	public function reset(): void
	{
		foreach ($this->collection as $linkParameter) {
			$linkParameter->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->collection as $linkParameter) {
			$linkParameter->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->collection as $linkParameter) {
			$params = $linkParameter->saveState($params);
		}

		return $params;
	}

}
