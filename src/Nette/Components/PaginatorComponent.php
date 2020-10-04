<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use InvalidArgumentException;
use Nette\Application\UI\Control;
use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\State\StateChangedEvent;
use WebChemistry\DataFilter\State\StateEventDispatcher;

final class PaginatorComponent extends Control implements PaginatorComponentInterface
{

	private DataFilter $dataFilter;

	private StateEventDispatcher $stateEventDispatcher;

	/** @var string[] */
	private array $templates = [
		'default' => __DIR__ . '/templates/paginator.latte',
		'bootstrap4' => __DIR__ . '/templates/paginator-bootstrap4.latte',
	];

	private string $template = 'default';

	private string $appendItemsFile = __DIR__ . '/templates/paginator-appendItems.latte';

	private ?string $appendItemsCaption = null;

	/** @var int[] */
	private array $steps;

	public function __construct(DataFilter $dataFilter, StateEventDispatcher $stateEventDispatcher)
	{
		$this->dataFilter = $dataFilter;
		$this->stateEventDispatcher = $stateEventDispatcher;
	}

	public function setTemplate(string $template): void
	{
		$this->template = $template;
	}

	public function setTemplateFile(string $file, string $template = 'default'): void
	{
		$this->files[$template] = $file;
	}

	public function setAppendItemsFile(string $appendItemsFile): void
	{
		$this->appendItemsFile = $appendItemsFile;
	}

	public function setAppendItemsCaption(?string $caption): void
	{
		$this->appendItemsCaption = $caption;
	}

	/**
	 * @param mixed ...$args
	 */
	public function render(...$args): void
	{
		$paginator = $this->dataFilter->getPaginator();
		if (!$paginator || $paginator->getPageCount() < 2) {
			//return;
		}

		if ($this->appendItemsCaption) {
			$this->renderAppendItems(...$args);
		} else {
			$this->renderDefault(...$args);
		}
	}

	/**
	 * @internal
	 */
	public function prevLink(): ?string
	{
		$page = $this->dataFilter->getHttpParameters()->getPage();
		if ($page !== 1) {
			return $this->stepLink($page - 1);
		}

		return null;
	}

	/**
	 * @return string|null
	 * @internal
	 */
	public function nextLink(): ?string
	{
		$page = $this->dataFilter->getHttpParameters()->getPage();
		if ($page < $this->dataFilter->getPaginator()->getPageCount()) {
			return $this->stepLink($page + 1);
		}

		return null;
	}

	/**
	 * @param int $step
	 * @return string
	 * @internal
	 */
	public function stepLink(int $step): string
	{
		return $this->link('paginate!', ['page' => $step]);
	}

	/**
	 * @return int[]
	 */
	public function getSteps(): array {
		if (!isset($this->steps)) {
			$paginator = $this->dataFilter->getPaginator();
			if (!$paginator) {
				$this->steps = [];
			} else {
				$arr = range(
					max($paginator->getFirstPage(), $paginator->getPage() - 2),
					min($paginator->getLastPage(), $paginator->getPage() + 2)
				);
				$count = 2;
				$quotient = ($paginator->getPageCount() - 1) / $count;
				for ($i = 0; $i <= $count; $i++) {
					$arr[] = (int) (round($quotient * $i) + $paginator->getFirstPage());
				}
				sort($arr);
				$this->steps = array_values(array_unique($arr));
			}
		}

		return $this->steps;
	}

	/**
	 * @internal
	 */
	public function handlePaginate(int $page): void {
		$this->dataFilter->getHttpParameters()->setPage($page);

		$this->stateEventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::PAGINATE));
	}

	private function renderAppendItems(?string $containerClass = null, ?string $buttonClass = null)
	{
		$paginator = $this->dataFilter->getPaginator();
		if ($paginator->isLast()) {
			return;
		}

		$template = $this->getTemplate();
		$template->setFile($this->appendItemsFile);

		$template->paginator = $paginator;
		$template->ajax = $this->dataFilter->getOptions()->isAjax();
		$template->nextLink = $this->nextLink();
		$template->appendItemsCaption = $this->appendItemsCaption;

		$template->containerClass = $containerClass;
		$template->buttonClass = $buttonClass;

		$template->render();
	}

	private function renderDefault(?string $template = null)
	{
		$paginator = $this->dataFilter->getPaginator();

		if ($template === null) {
			$template = $this->template;
		}

		$file = $this->templates[$template] ?? null;
		if (!$file) {
			throw new InvalidArgumentException(sprintf('Template %s not exists', $template));
		}

		$template = $this->getTemplate();
		$template->setFile($file);

		$template->paginator = $paginator;
		$template->steps = $this->getSteps();
		$template->ajax = $this->dataFilter->getOptions()->isAjax();
		$template->pageCount = $paginator->getPageCount();
		$template->page = $paginator->getPage();
		$template->prevLink = $this->prevLink();
		$template->nextLink = $this->nextLink();

		$template->render();
	}

}
