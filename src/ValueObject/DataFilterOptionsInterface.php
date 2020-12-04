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

	public function getOrderBy(string $id): OrderBy;

	public function getDefaultOrderBy(): ?OrderBy;

	/**
	 * @return Link[]
	 */
	public function getLinks(): array;

	/**
	 * @return Switcher[]
	 */
	public function getSwitchers(): array;

	/**
	 * @return FormObject[]
	 */
	public function getForms(): array;

	public function isEnabledLinkEvents(): bool;

}
