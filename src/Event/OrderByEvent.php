<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Event;

use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\EventDispatcher\Event;
use WebChemistry\DataFilter\ValueObject\OrderBy;

final class OrderByEvent extends Event
{

	private OrderBy $orderBy;

	private DataFilter $dataFilter;

	public function __construct(OrderBy $orderBy, DataFilter $dataFilter)
	{
		$this->orderBy = $orderBy;
		$this->dataFilter = $dataFilter;
	}

	public function getOrderBy(): OrderBy
	{
		return $this->orderBy;
	}

	public function getDataFilter(): DataFilter
	{
		return $this->dataFilter;
	}

}
