<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use LogicException;

final class SearchParameter
{

	private ?string $search;

	public function __construct(?string $search)
	{
		$this->search = $search;
	}

	public function isOk(): bool
	{
		return (bool) $this->search;
	}

	public function startsWith(): string
	{
		$this->assert();

		return $this->search . '%';
	}

	public function endsWith(): string
	{
		$this->assert();

		return '%' . $this->search;
	}

	public function contains(): string
	{
		$this->assert();

		return '%' . $this->search . '%';
	}

	public function get(): ?string
	{
		return $this->search;
	}

	private function assert(): void
	{
		if (!$this->isOk()) {
			throw new LogicException('Cannot use search parameters. Check first isOk()');
		}
	}

}
