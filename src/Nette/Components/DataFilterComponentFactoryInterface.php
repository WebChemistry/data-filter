<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use WebChemistry\DataFilter\DataFilter;

interface DataFilterComponentFactoryInterface
{
	
	public function create(DataFilter $dataFilter): DataFilterComponent;

}
