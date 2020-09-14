<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use InvalidArgumentException;

final class DataFilterOptionsBuilder
{

	/** @var mixed[] */
	private array $options = [
		'ajax' => false,
		'limit' => null,
		'orderBy' => [],
		'defaultOrderBy' => null,
		'limits' => [],
	];

	public function addLimit(int $limit): void
	{
		$this->options['limits'][$limit] = $limit;
	}

	public function setLimit(int $limit): void
	{
		$this->options['limit'] = $limit === null ? null : max(1, $limit);
	}

	public function addOrderBy(OrderBy $order): void
	{
		$this->options['orderBy'][$order->getId()] = $order;
	}

	public function setDefaultOrderBy(OrderBy $orderBy): void
	{
		if (!isset($this->options['orderBy'][$orderBy->getId()])) {
			throw new InvalidArgumentException(sprintf('Order by %s not exists in list', $orderBy->getId()));
		}

		$this->options['defaultOrderBy'] = $orderBy;
	}

	public function setFirstAsDefaultOrderBy(): void
	{
		if (!$this->options['orderBy']) {
			throw new InvalidArgumentException('Order by must not be empty');
		}

		$this->options['defaultOrderBy'] = current($this->options['orderBy']);
	}

	public function setAjax(bool $ajax = true): void
	{
		$this->options['ajax'] = $ajax;
	}

	public function build(): DataFilterOptionsInterface
	{
		return new DataFilterOptions($this->options);
	}

}
