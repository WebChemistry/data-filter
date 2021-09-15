<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

final class CallableDoctrineDataSource implements DataSourceInterface
{

	/** @var callable */
	private $factory;

	public function __construct(callable $factory)
	{
		$this->factory = $factory;
	}

	public function getItemCount(): int
	{
		$decorated = new DoctrineDataSource(($this->factory)(false));

		return $decorated->getItemCount();
	}

	public function getData(?int $limit = null, ?int $offset = null): iterable
	{
		$decorated = new DoctrineDataSource(($this->factory)(true));

		return $decorated->getData($limit, $offset);
	}

}
