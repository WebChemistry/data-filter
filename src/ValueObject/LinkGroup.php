<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

final class LinkGroup
{

	private string $id;

	/** @var Link[] */
	private array $links = [];

	public function __construct(string $id)
	{
		$this->id = $id;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function addLink(string $id, string $caption, $value, bool $active = false): self
	{
		$this->links[$id] = new Link($id, $caption, $value, $active);

		return $this;
	}

	public function getLink(string $id): ?Link
	{
		return $this->links[$id] ?? null;
	}

	/**
	 * @return Link[]
	 */
	public function getLinks(): array
	{
		return $this->links;
	}

}
