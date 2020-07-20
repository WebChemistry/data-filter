<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\Nette\Template\NetteTemplateFacade;
use WebChemistry\DataFilter\State\StateChangedEvent;
use WebChemistry\DataFilter\State\StateEventDispatcher;

class DataFilterComponent extends Control
{

	private DataFilter $dataFilter;

	private NetteTemplateFacade $templateFacade;

	private StateEventDispatcher $stateEventDispatcher;

	public function __construct(DataFilter $dataFilter)
	{
		$this->dataFilter = $dataFilter;

		$this->templateFacade = new NetteTemplateFacade($dataFilter, $this);
		$this->stateEventDispatcher = $this->createStateDispatcher();
	}

	public function getDataFilter(): DataFilter
	{
		return $this->dataFilter;
	}

	public function getFacade(): NetteTemplateFacade
	{
		return $this->templateFacade;
	}

	public function getPaginatorComponent(): PaginatorComponentInterface
	{
		$paginator = $this['paginator'];
		assert($paginator instanceof PaginatorComponentInterface);

		return $paginator;
	}

	public function getResetLink(): string
	{
		return $this->link('reset!');
	}

	protected function createComponentPaginator(): PaginatorComponentInterface
	{
		return new PaginatorComponent($this->dataFilter, $this->stateEventDispatcher);
	}

	protected function createComponentSearch(): Form
	{
		$form = new Form();
		$form->addText('search')
			->setDefaultValue($this->dataFilter->getHttpParameters()->getSearch()->get())
			->setNullable();

		$form->addSubmit('send');

		$form->onSuccess[] = function (Form $form, array $values): void {
			$this->dataFilter->getHttpParameters()->setSearch($values['search']);

			$this->stateEventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::SEARCH));
		};

		return $form;
	}

	protected function createComponentOrderBy(): Form
	{
		$form = new Form();

		$values = [];
		foreach ($this->dataFilter->getOptions()->getOrderByList() as $orderBy) {
			$values[$orderBy->getId()] = $orderBy->getLabel();
		}

		$orderBy = $this->dataFilter->getHttpParameters()->getOrderBy();
		$form->addSelect('orderBy', null, $values)
			->setDefaultValue($orderBy ? $orderBy->getId() : array_key_first($values));

		$form->addSubmit('send');

		$form->onSuccess[] = function (Form $form, array $values): void {
			$this->dataFilter->getHttpParameters()->setOrderBy($values['orderBy']);

			$this->stateEventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::ORDER_BY));
		};

		return $form;
	}

	public function handleReset(): void
	{
		$this->dataFilter->getHttpParameters()->reset();

		$this->stateEventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::RESET));
	}

	public function loadState(array $params): void
	{
		$this->dataFilter->getHttpParameters()->loadState($params);
	}

	public function saveState(array &$params): void
	{
		$params = $this->dataFilter->getHttpParameters()->saveState();
	}

	private function createStateDispatcher(): StateEventDispatcher
	{
		$dispatcher = StateEventDispatcher::createWithEventDispatcher($this->dataFilter->getStateEventDispatcher());
		$dispatcher->addEventListener(function (): void {
			if (!$this->getPresenter()->isAjax()) {
				$this->redirect('this');
			}
		});

		return $dispatcher;
	}

}
