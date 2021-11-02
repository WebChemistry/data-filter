<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use InvalidArgumentException;
use WebChemistry\DataFilter\HttpParameter\ValueObject\LinkParameter;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;
use WebChemistry\DataFilter\ValueObject\Link;

final class LinksHttpParameter implements HttpParameterInterface
{

	/** @var LinkParameter[] */
	private array $links = [];

	/**
	 * @param Link[] $links
	 */
	public function __construct(DataFilterOptionsInterface $options)
	{
		foreach ($options->getLinks() as $link) {
			$this->links[$link->getId()] = new LinkParameter($link, $this->getHttpId());
		}
	}

	public function getHttpId(): string
	{
		return 'link_';
	}

	public function hasLink(string $link): bool
	{
		return isset($this->links[$link]);
	}

	public function getLink(string $link): LinkParameter
	{
		return $this->links[$link] ?? throw new InvalidArgumentException(sprintf('Link "%s" does not exist.', $link));
	}

	public function reset(): void
	{
		foreach ($this->links as $link) {
			$link->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->links as $link) {
			$link->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->links as $link) {
			$params = $link->saveState($params);
		}

		return $params;
	}

}
