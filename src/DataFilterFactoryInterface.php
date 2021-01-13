<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter;

use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;

interface DataFilterFactoryInterface
{

	public function create(callable $dataSourceFactory, DataFilterOptionsInterface $options): DataFilter;
	
}
