<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components\ValueObject;

use WebChemistry\DataFilter\HttpParameter\ValueObject\LinkParameter;

class LinkTemplate
{

	private LinkParameter $linkParameter;

	private string $url;

	private $value;

	public function __construct(LinkParameter $linkParameter, $value, string $url)
	{
		$this->linkParameter = $linkParameter;
		$this->value = $value;
		$this->url = $url;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getCaption(): string
	{
		return $this->linkParameter->getLink()->getCaption();
	}

	public function isActive(): bool
	{
		return !$this->linkParameter->isDefault() && $this->value === $this->linkParameter->getValue();
	}

	public function getUrl(): string
	{
		return $this->url;
	}

}
