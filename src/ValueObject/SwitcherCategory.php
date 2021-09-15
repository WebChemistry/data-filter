<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

final class SwitcherCategory
{

	private string $category;

	/** @var Switcher[] */
	private array $switchers = [];

	public function __construct(string $category)
	{
		$this->category = $category;
	}

	public function addSwitcher(string $id, string $caption, bool $default = false, array $options = []): self
	{
		$this->switchers[$id] = new Switcher($this->category . '_' . $id, $caption, $default, $this->category, $options);

		return $this;
	}

	/**
	 * @return Switcher[]
	 */
	public function getSwitchers(): array
	{
		return $this->switchers;
	}


}
