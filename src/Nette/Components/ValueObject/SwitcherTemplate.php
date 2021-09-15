<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components\ValueObject;

use WebChemistry\DataFilter\HttpParameter\ValueObject\SwitcherParameter;

class SwitcherTemplate
{

	private SwitcherParameter $switcher;

	private string $url;

	public function __construct(SwitcherParameter $switcher, string $url)
	{
		$this->switcher = $switcher;
		$this->url = $url;
	}

	public function getId(): string
	{
		return $this->switcher->getSwitcher()->getId();
	}

	public function getCategory(): ?string
	{
		return $this->switcher->getSwitcher()->getCategory();
	}

	public function isActive(): bool
	{
		return $this->switcher->getValue();
	}

	public function getCaption(): string
	{
		return $this->switcher->getSwitcher()->getCaption();
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getOption(string|int $index): mixed
	{
		return $this->switcher->getSwitcher()->getOption($index);
	}

}
