<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Template;

use WebChemistry\DataFilter\DataFilter;

class TemplateFacade
{

	protected DataFilter $dataFilter;

	public function __construct(DataFilter $dataFilter)
	{
		$this->dataFilter = $dataFilter;
	}

	public function getData(): iterable
	{
		return $this->dataFilter->getData();
	}

	public function getLazyData(): iterable
	{
		return $this->dataFilter->getLazyData();
	}

	public function getItemCount(): int
	{
		return $this->dataFilter->getItemCount();
	}

}
