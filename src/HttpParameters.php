<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter;

use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;
use WebChemistry\DataFilter\ValueObject\OrderBy;
use WebChemistry\DataFilter\ValueObject\SearchParameter;

class HttpParameters
{

	private DataFilterOptionsInterface $options;

	/** @var mixed[]|null */
	private array $parameters;

	public function __construct(DataFilterOptionsInterface $options)
	{
		$this->options = $options;

		$this->reset();
	}

	public function reset(): void
	{
		$this->parameters = [
			'search' => new SearchParameter(null),
			'order' => null,
			'limit' => null,
			'page' => 1,
		];
	}

	public function getAll(): array
	{
		return $this->parameters;
	}

	public function getLimit(): ?int
	{
		return $this->parameters['limit'];
	}

	public function setPage(int $page): void
	{
		$this->parameters['page'] = max(1, $page);
	}

	public function getPage(): int
	{
		return $this->parameters['page'];
	}

	public function getSearch(): SearchParameter
	{
		return $this->parameters['search'];
	}

	public function paramIssetAndNum(array $params, string $name): bool
	{
		return isset($params[$name]) && is_numeric($params[$name]);
	}

	public function setSearch(?string $search): void
	{
		$this->parameters['search'] = new SearchParameter($search);
	}

	public function setOrderBy(string $orderById): void
	{
		$this->parameters['order'] = $this->options->getOrderByList()[$orderById] ?? null;
	}

	public function getOrderBy(): ?OrderBy
	{
		return $this->parameters['order'];
	}

	/**
	 * @param mixed[] $params
	 * @internal
	 */
	public function loadState(array $params): void
	{
		$this->parameters = [
			'search' => new SearchParameter($params['search'] ?? null),
			'order' => $this->options->getDefaultOrderBy(),
			'limit' => $this->paramIssetAndNum($params, 'limit') ? max(1, (int) $params['limit']) : $this->options->getLimit(),
			'page' => $this->paramIssetAndNum($params, 'page') ? max(1, (int) $params['page']) : 1,
		];

		if (isset($params['order'])) {
			$this->setOrderBy($params['order']);
		}
	}

	/**
	 * @internal
	 */
	public function saveState(): array
	{
		$params = $this->parameters;
		$params['search'] = $params['search']->get();

		if ($params['page'] <= 1) {
			unset($params['page']);
		}

		if ($params['limit'] === $this->options->getLimit()) {
			unset($params['limit']);
		}

		if ($params['order'] instanceof OrderBy) {
			$params['order'] = $params['order']->getId();
		}

		if (($orderBy = $this->options->getDefaultOrderBy()) && $orderBy->getId() === $params['order']) {
			unset($params['order']);
		}

		return array_filter($params, fn ($value) => $value !== null);
	}

}
