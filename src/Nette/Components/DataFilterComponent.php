<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use InvalidArgumentException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\Event\OrderByEvent;
use WebChemistry\DataFilter\Event\SwitcherEvent;
use WebChemistry\DataFilter\EventDispatcher\EventDispatcher;
use WebChemistry\DataFilter\EventDispatcher\EventInterface;
use WebChemistry\DataFilter\HttpParameter\LinksHttpParameter;
use WebChemistry\DataFilter\HttpParameter\OrderByHttpParameter;
use WebChemistry\DataFilter\HttpParameter\SearchHttpParameter;
use WebChemistry\DataFilter\HttpParameter\SwitchersHttpParameter;
use WebChemistry\DataFilter\Nette\Components\ValueObject\LinkTemplate;
use WebChemistry\DataFilter\Nette\Components\ValueObject\OrderByTemplate;
use WebChemistry\DataFilter\Nette\Components\ValueObject\SwitcherTemplate;
use WebChemistry\DataFilter\Nette\Template\NetteTemplateFacade;
use WebChemistry\DataFilter\State\StateChangedEvent;

class DataFilterComponent extends Control
{

	private DataFilter $dataFilter;

	private NetteTemplateFacade $templateFacade;

	private EventDispatcher $eventDispatcher;

	public function __construct(DataFilter $dataFilter)
	{
		$this->dataFilter = $dataFilter;

		$this->templateFacade = new NetteTemplateFacade($dataFilter, $this);

		$this->eventDispatcher = new EventDispatcher();
		$this->eventDispatcher->addEventListener(
			null,
			fn (EventInterface $event) => $this->dataFilter->getEventDispatcher()->dispatch($event)
		);
		$this->eventDispatcher->addEventListener(StateChangedEvent::class, function (): void {
			if (!$this->getPresenter()->isAjax()) {
				$this->redirect('this');
			}
		});
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
		return new PaginatorComponent($this->dataFilter, $this->eventDispatcher);
	}

	protected function createComponentSearch(): Form
	{
		$form = new Form();
		$form->addText('search')
			->setDefaultValue(
				$this->dataFilter->getHttpParameters()
					->getParameter(SearchHttpParameter::class)
					->getValue()
					->get()
			)
			->setNullable();

		$form->addSubmit('send');

		$form->onSuccess[] = function (Form $form, array $values): void {
			$this->dataFilter->getHttpParameters()
				->getParameter(SearchHttpParameter::class)
				->setValue($values['search']);

			$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::SEARCH, $this->dataFilter));
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

		$orderBy = $this->dataFilter->getHttpParameters()
			->getParameter(OrderByHttpParameter::class)
			->getValue();

		$form->addSelect('orderBy', null, $values)
			->setDefaultValue($orderBy ? $orderBy->getId() : array_key_first($values));

		$form->addSubmit('send');

		$form->onSuccess[] = function (Form $form, array $values): void {
			$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(OrderByHttpParameter::class);
			$httpParameter->setHttpValue($values['orderBy']);

			$this->eventDispatcher->dispatch(new OrderByEvent($httpParameter->getValue(), $this->dataFilter));
			$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::ORDER_BY, $this->dataFilter));
		};

		return $form;
	}

	protected function createComponentForm(): Multiplier
	{
		return new Multiplier(function (string $id) {
			$forms = $this->dataFilter->getOptions()->getForms();

			if (!isset($forms[$id])) {
				throw new InvalidArgumentException(sprintf('Form %s not exists', $id));
			}

			$form = $forms[$id]->getForm();

			$form->setDefaults(
				$this->dataFilter->getHttpParameters()
					->getForms()
					->getValue()
					->get($id)
					->getValue()
			);

			$form->onSuccess[] = function (Form $form, array $values) use($id): void {
				$this->dataFilter->getHttpParameters()
					->getForms()
					->getValue()
					->get($id)
					->setValue($values);

				$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::FORM, $this->dataFilter));
			};

			return $form;
		});
	}

	public function getForm(string $id): Form
	{
		return $this['form'][$id];
	}

	public function handleReset(): void
	{
		$this->dataFilter->getHttpParameters()->reset();

		$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::RESET, $this->dataFilter));
	}

	private function extractPersistentParams(array $params): array
	{
		$return = [];

		foreach (['id', 'val'] as $key) {
			if (isset($params[$key])) {
				$return[$key] = $params[$key];
			}
		}

		return $return;
	}

	public function loadState(array $params): void
	{
		parent::loadState($this->extractPersistentParams($params));

		$this->dataFilter->getHttpParameters()->loadState($params);
	}

	public function saveState(array &$params): void
	{
		$params = $this->dataFilter->getHttpParameters()->saveState();
	}

	public function getOrderBy(string $id): OrderByTemplate
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(OrderByHttpParameter::class);
		$orderBy = $this->dataFilter->getOptions()->getOrderBy($id);

		if (!$this->dataFilter->getOptions()->isEnabledLinkEvents()) {
			$url = $this->link('this', [
				$httpParameter->getHttpId() => $id,
			]);
		} else {
			$url = $this->link('orderBy!', $id);
		}

		return new OrderByTemplate($orderBy, $url, $httpParameter->getValue() === $orderBy);
	}

	public function getLink(string $id, $value): LinkTemplate
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(LinksHttpParameter::class);
		$collection = $httpParameter->getValue();
		$link = $collection->get($id);

		if (!$this->dataFilter->getOptions()->isEnabledLinkEvents()) {
			$url = $this->link('this', [
				$link->getHttpId() => $value,
			]);
		} else {
			$url = $this->link('link!', $id, $value);
		}

		return new LinkTemplate($link, $value, $url);
	}

	public function getSwitcher(string $id): SwitcherTemplate
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(SwitchersHttpParameter::class);
		$collection = $httpParameter->getValue();
		$switcher = $collection->get($id);

		if (!$this->dataFilter->getOptions()->isEnabledLinkEvents()) {
			$url = $this->link('this', [
				$switcher->getHttpId() => $switcher->getHttpValue(),
			]);
		} else {
			$url = $this->link('switch!', $id, $switcher->getHttpValue(false));
		}

		return new SwitcherTemplate($switcher, $url);
	}

	/**
	 * @return SwitcherTemplate[]
	 */
	public function getSwitchers(): array
	{
		$switchers = [];
		foreach ($this->dataFilter->getOptions()->getSwitchers() as $switcher) {
			$switchers[] = $this->getSwitcher($switcher->getId());
		}

		return $switchers;
	}

	public function handleLink(string $id, $val)
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(LinksHttpParameter::class);
		$collection = $httpParameter->getValue();
		try {
			$link = $collection->get($id);
		} catch (InvalidArgumentException $exception) {
			$this->redirect('this');
		}

		$link->setValue($val);

		$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::LINK, $this->dataFilter));
	}

	public function handleSwitch(string $id, bool $val)
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(SwitchersHttpParameter::class);
		$collection = $httpParameter->getValue();
		try {
			$switcher = $collection->get($id);
		} catch (InvalidArgumentException $exception) {
			$this->redirect('this');
		}

		$switcher->setValue($val);

		$this->eventDispatcher->dispatch(new SwitcherEvent($switcher, $this->dataFilter));
		$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::SWITCHER, $this->dataFilter));
	}

	public function handleOrderBy(string $id)
	{
		$httpParameter = $this->dataFilter->getHttpParameters()->getParameter(OrderByHttpParameter::class);

		try {
			$orderBy = $this->dataFilter->getOptions()->getOrderBy($id);
		} catch (InvalidArgumentException $exception) {
			$this->redirect('this');
		}

		$httpParameter->setValue($orderBy);

		$this->eventDispatcher->dispatch(new OrderByEvent($orderBy, $this->dataFilter));
		$this->eventDispatcher->dispatch(new StateChangedEvent(StateChangedEvent::ORDER_BY, $this->dataFilter));
	}

}
