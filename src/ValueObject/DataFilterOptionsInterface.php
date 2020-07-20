<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

interface DataFilterOptionsInterface
{

	public function getLimit(): ?int;

	public function isAjax(): bool;

	/**
	 * @return int[]
	 */
	public function getLimits(): array;

	/**
	 * @return OrderBy[]
	 */
	public function getOrderByList(): array;

	public function getDefaultOrderBy(): ?OrderBy;

}
