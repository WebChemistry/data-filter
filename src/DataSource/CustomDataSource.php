<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

final class CustomDataSource implements DataSourceInterface
{

	/** @var callable */
	private $factoryData;

	/** @var callable */
	private $factoryItemCount;

	public function __construct(callable $factoryData, callable $factoryItemCount)
	{
		$this->factoryData = $factoryData;
		$this->factoryItemCount = $factoryItemCount;
	}

	public function getItemCount(): int
	{
		return ($this->factoryItemCount)();
	}

	public function getData(?int $limit = null, ?int $offset = null): iterable
	{
		return ($this->factoryData)($limit, $offset);
	}

}
