<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter;

use InvalidArgumentException;
use LogicException;
use WebChemistry\DataFilter\HttpParameter\FormsHttpParameter;
use WebChemistry\DataFilter\HttpParameter\HttpParameterInterface;
use WebChemistry\DataFilter\HttpParameter\LimitHttpParameter;
use WebChemistry\DataFilter\HttpParameter\LinksHttpParameter;
use WebChemistry\DataFilter\HttpParameter\OrderByHttpParameter;
use WebChemistry\DataFilter\HttpParameter\PageHttpParameter;
use WebChemistry\DataFilter\HttpParameter\SearchHttpParameter;
use WebChemistry\DataFilter\HttpParameter\SwitchersHttpParameter;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;
use WebChemistry\DataFilter\ValueObject\Link;
use WebChemistry\DataFilter\ValueObject\LinkGroup;
use WebChemistry\DataFilter\ValueObject\OrderBy;
use WebChemistry\DataFilter\ValueObject\SearchParameter;

class HttpParameters
{

	/** @var HttpParameterInterface[] */
	private array $parameters;

	public function __construct(DataFilterOptionsInterface $options)
	{
		$this->parameters = [
			LimitHttpParameter::class => new LimitHttpParameter($options->getLimit()),
			OrderByHttpParameter::class => new OrderByHttpParameter($options->getDefaultOrderBy(), $options->getOrderByList()),
			PageHttpParameter::class => new PageHttpParameter(),
			SearchHttpParameter::class => new SearchHttpParameter(),
			LinksHttpParameter::class => new LinksHttpParameter($options->getLinks()),
			SwitchersHttpParameter::class => new SwitchersHttpParameter($options->getSwitchers()),
			FormsHttpParameter::class => new FormsHttpParameter($options->getForms()),
		];
	}

	public function reset(): void
	{
		foreach ($this->parameters as $parameter) {
			$parameter->reset();
		}
	}

	public function getSearch(): SearchParameter
	{
		return $this->getParameter(SearchHttpParameter::class)
			->getValue();
	}

	public function getForms(): FormsHttpParameter
	{
		return $this->getParameter(FormsHttpParameter::class);
	}

	public function getSwitchers(): SwitchersHttpParameter
	{
		return $this->getParameter(SwitchersHttpParameter::class);
	}

	public function getOrderBy(): OrderBy
	{
		$order = $this->getParameter(OrderByHttpParameter::class)
			->getValue();

		if (!$order) {
			throw new LogicException('Order by is null');
		}

		return $order;
	}

	public function getParameter(string $class): HttpParameterInterface
	{
		if (!isset($this->parameters[$class])) {
			throw new InvalidArgumentException(sprintf('Http parameters %s not exists', $class));
		}

		return $this->parameters[$class];
	}

	/**
	 * @param mixed[] $params
	 * @internal
	 */
	public function loadState(array $params): void
	{
		foreach ($this->parameters as $parameter) {
			$parameter->loadState($params);
		}
	}

	/**
	 * @internal
	 */
	public function saveState(): array
	{
		$params = [];
		foreach ($this->parameters as $parameter) {
			$params = $parameter->saveState($params);
		}

		return $params;
	}

}
