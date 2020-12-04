<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\ValueObject\LinkCollection;
use WebChemistry\DataFilter\HttpParameter\ValueObject\LinkParameter;
use WebChemistry\DataFilter\ValueObject\Link;

final class LinksHttpParameter implements HttpParameterInterface
{

	private LinkCollection $value;

	/**
	 * @param Link[] $links
	 */
	public function __construct(array $links)
	{
		$this->value = new LinkCollection($links, 'link_');
	}

	public function getValue(): LinkCollection
	{
		return $this->value;
	}

	public function reset(): void
	{
		$this->value->reset();
	}

	public function loadState(array $params): void
	{
		$this->value->loadState($params);
	}

	public function saveState(array $params): array
	{
		return $this->value->saveState($params);
	}

}
