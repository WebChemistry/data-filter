<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

final class FixedCountDataSource implements DataSourceInterface
{

	/** @var callable */
	private $getter;

	private DataSourceInterface $dataSource;

	public function __construct(callable $getter, DataSourceInterface $dataSource)
	{
		$this->getter = $getter;
		$this->dataSource = $dataSource;
	}

	public function getItemCount(): int
	{
		return ($this->getter)();
	}

	public function getData(?int $limit = null, ?int $offset = null): iterable
	{
		return $this->dataSource->getData($limit, $offset);
	}

}
