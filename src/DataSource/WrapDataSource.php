<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

class WrapDataSource implements DataSourceInterface
{

	private DataSourceInterface $dataSource;

	/**
	 * @phpstan-var callable(iterable): iterable
	 * @var callable
	 */
	private $callback;

	/**
	 * @phpstan-param callable(iterable): iterable $callback
	 */
	public function __construct(DataSourceInterface $dataSource, callable $callback)
	{
		$this->dataSource = $dataSource;
		$this->callback = $callback;
	}

	public function getItemCount(): int
	{
		return $this->dataSource->getItemCount();
	}

	/**
	 * @return mixed[]
	 */
	public function getData(?int $limit = null, ?int $offset = null): iterable
	{
		return ($this->callback)($this->dataSource->getData($limit, $offset));
	}

}
