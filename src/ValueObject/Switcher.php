<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

final class Switcher
{

	private string $id;

	private string $caption;

	private bool $default;

	private ?string $category;

	public function __construct(string $id, string $caption, bool $default, ?string $category = null)
	{
		$this->id = $id;
		$this->caption = $caption;
		$this->default = $default;
		$this->category = $category;
	}

	public function getCategory(): ?string
	{
		return $this->category;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getCaption(): string
	{
		return $this->caption;
	}

	public function getDefault(): bool
	{
		return $this->default;
	}

}
