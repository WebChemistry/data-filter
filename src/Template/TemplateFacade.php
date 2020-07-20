<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Template;

use WebChemistry\DataFilter\DataFilter;

class TemplateFacade
{

	private DataFilter $dataFilter;

	public function __construct(DataFilter $dataFilter)
	{
		$this->dataFilter = $dataFilter;
	}

	public function getData(): iterable
	{
		return $this->dataFilter->getData();
	}

}
