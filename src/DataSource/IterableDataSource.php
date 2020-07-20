<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

use Iterator;
use LimitIterator;
use Traversable;

final class IterableDataSource implements DataSourceInterface
{

	private iterable $data;

	public function __construct(iterable $data)
	{
		$this->data = $data;
	}

	public function getItemCount(): int
	{
		return is_array($this->data) ? count($this->data) : $this->iteratorCount();
	}

	public function getData(?int $limit = null, ?int $offset = null): iterable
	{
		if ($limit === null && $offset === null) {
			return $this->data;
		}

		if (is_array($this->data)) {
			return array_slice($this->data, $offset, $limit);
		}

		if ($this->data instanceof Iterator) {
			return new LimitIterator($this->data, $offset, $limit);
		}

		return array_slice([...$this->data], $offset, $limit);
	}

	private function iteratorCount(): int
	{
		return $this->data instanceof Traversable ? iterator_count($this->data) : count([...$this->data]);
	}

}
