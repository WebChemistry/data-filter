<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Event;

use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\EventDispatcher\Event;
use WebChemistry\DataFilter\HttpParameter\ValueObject\SwitcherParameter;
use WebChemistry\DataFilter\ValueObject\Switcher;

final class SwitcherEvent extends Event
{

	private SwitcherParameter $switcher;

	private DataFilter $dataFilter;

	public function __construct(SwitcherParameter $switcher, DataFilter $dataFilter)
	{
		$this->switcher = $switcher;
		$this->dataFilter = $dataFilter;
	}

	public function getSwitcherParameter(): SwitcherParameter
	{
		return $this->switcher;
	}

	public function getDataFilter(): DataFilter
	{
		return $this->dataFilter;
	}

}
