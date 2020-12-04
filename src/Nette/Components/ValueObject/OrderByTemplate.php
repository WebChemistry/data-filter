<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components\ValueObject;

use WebChemistry\DataFilter\ValueObject\OrderBy;

final class OrderByTemplate
{

	private OrderBy $orderBy;

	private string $url;

	private bool $active;

	public function __construct(OrderBy $orderBy, string $url, bool $active)
	{
		$this->orderBy = $orderBy;
		$this->url = $url;
		$this->active = $active;
	}

	public function getCaption(): string
	{
		return $this->orderBy->getLabel();
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function getOrderBy(): OrderBy
	{
		return $this->orderBy;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

}
