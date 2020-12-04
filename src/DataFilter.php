<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter;

use Nette\Utils\Paginator;
use WebChemistry\DataFilter\DataSource\DataSourceInterface;
use WebChemistry\DataFilter\EventDispatcher\EventDispatcher;
use WebChemistry\DataFilter\HttpParameter\LimitHttpParameter;
use WebChemistry\DataFilter\HttpParameter\PageHttpParameter;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;

class DataFilter
{

	/** @var callable(): DataSourceInterface */
	private $dataSourceFactory;

	/** @var callable(mixed $values): void */
	private $dataDecorator;

	private iterable $data;

	private int $itemCount;

	private DataSourceInterface $dataSource;

	private Paginator $paginator;

	private HttpParameters $httpParameters;

	private DataFilterOptionsInterface $options;

	private EventDispatcher $eventDispatcher;

	/**
	 * @var callable(): DataSourceInterface $dataSourceFactory
	 */
	public function __construct(callable $dataSourceFactory, DataFilterOptionsInterface $options)
	{
		$this->dataSourceFactory = $dataSourceFactory;
		$this->options = $options;

		$this->httpParameters = new HttpParameters($options);
		$this->eventDispatcher = new EventDispatcher();
	}

	public function getEventDispatcher(): EventDispatcher
	{
		return $this->eventDispatcher;
	}

	public function getHttpParameters(): HttpParameters
	{
		return $this->httpParameters;
	}

	public function getDataSource(): DataSourceInterface
	{
		if (!isset($this->dataSource)) {
			$this->dataSource = ($this->dataSourceFactory)($this->httpParameters);
		}

		return $this->dataSource;
	}

	public function getOptions(): DataFilterOptionsInterface
	{
		return $this->options;
	}

	public function getData(): iterable
	{
		if (!isset($this->data)) {
			$paginator = $this->getPaginator();

			$this->data = $this->getDataSource()->getData(
				$paginator === null ? null : $paginator->getLength(),
				$paginator === null ? null : $paginator->getOffset()
			);

			if ($this->dataDecorator) {
				$this->data = ($this->dataDecorator)($this->data, $this);
			}
		}

		return $this->data;
	}

	public function getItemCount(): int
	{
		if (!isset($this->itemCount)) {
			$this->itemCount = $this->getDataSource()->getItemCount();
		}

		return $this->itemCount;
	}

	public function getPaginator(): ?Paginator
	{
		if ($this->options->getLimit() === null) {
			return null;
		}

		if (!isset($this->paginator)) {
			$this->paginator = new Paginator();

			$this->paginator->setPage(
				$this->httpParameters->getParameter(PageHttpParameter::class)->getValue()
			);
			$this->paginator->setItemsPerPage(
				$this->httpParameters->getParameter(LimitHttpParameter::class)->getValue()
			);
			$this->paginator->setItemCount($this->getDataSource()->getItemCount());
		}

		return $this->paginator;
	}

	public function setDataDecorator(callable $decorator): void
	{
		$this->dataDecorator = $decorator;
	}

}
