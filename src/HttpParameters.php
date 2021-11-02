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

class HttpParameters
{

	/** @var HttpParameterInterface[] */
	private array $parameters;

	/** @var array<string, bool> */
	private array $ids = [];

	public function __construct(DataFilterOptionsInterface $options)
	{
		$this->addHttpParameter(new LimitHttpParameter($options));
		$this->addHttpParameter(new OrderByHttpParameter($options));
		$this->addHttpParameter(new PageHttpParameter($options));
		$this->addHttpParameter(new SearchHttpParameter($options));
		$this->addHttpParameter(new LinksHttpParameter($options));
		$this->addHttpParameter(new SwitchersHttpParameter($options));
		$this->addHttpParameter(new FormsHttpParameter($options));
	}

	/**
	 * @return static
	 */
	public function addHttpParameter(HttpParameterInterface $httpParameter)
	{
		$id = $httpParameter->getHttpId();

		if (isset($this->ids[$id])) {
			throw new LogicException(sprintf('Http parameter with prefix "%s" already exists.', $id));
		}

		$this->parameters[$httpParameter::class] = $httpParameter;
		$this->ids[$id] = true;

		return $this;
	}

	public function reset(): void
	{
		foreach ($this->parameters as $parameter) {
			$parameter->reset();
		}
	}

	public function getSearch(): SearchHttpParameter
	{
		return $this->getParameter(SearchHttpParameter::class);
	}

	public function getForms(): FormsHttpParameter
	{
		return $this->getParameter(FormsHttpParameter::class);
	}

	public function getSwitchers(): SwitchersHttpParameter
	{
		return $this->getParameter(SwitchersHttpParameter::class);
	}

	public function getLinks(): LinksHttpParameter
	{
		return $this->getParameter(LinksHttpParameter::class);
	}

	public function getOrderBy(): OrderByHttpParameter
	{
		return $this->getParameter(OrderByHttpParameter::class);
	}

	/**
	 * @template T
	 * @param class-string<T> $class
	 * @return T
	 */
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
