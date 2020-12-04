<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use InvalidArgumentException;
use Nette\Application\UI\Form;

final class DataFilterOptionsBuilder
{

	/** @var mixed[] */
	private array $options = [
		'ajax' => false,
		'enabledLinkEvents' => false,
		'limit' => null,
		'orderBy' => [],
		'defaultOrderBy' => null,
		'limits' => [],
		'links' => [],
		'switchers' => [],
		'forms' => [],
	];

	/** @var SwitcherCategory[] */
	private array $switcherCategories = [];

	public function enableLinkEvents(): void
	{
		$this->options['enabledLinkEvents'] = true;
	}

	public function addForm(string $id, Form $form): void
	{
		$this->options['forms'][$id] = new FormObject($id, $form);
	}

	public function addSwitcher(string $id, string $caption, bool $default = false): Switcher
	{
		return $this->options['switchers'][$id] = new Switcher($id, $caption, $default);
	}

	public function addSwitcherCategory(string $category): SwitcherCategory
	{
		return $this->switcherCategories[$category] = new SwitcherCategory($category);
	}

	public function addLink(string $id, string $caption, $default = null): Link
	{
		return $this->options['links'][$id] = new Link($id, $caption, $default);
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
		if ($this->options['ajax']) {
			$this->options['enabledLinkEvents'] = true;
		}

		foreach ($this->switcherCategories as $category) {
			foreach ($category->getSwitchers() as $switcher) {
				$this->options['switchers'][$switcher->getId()] = $switcher;
			}
		}

		return new DataFilterOptions($this->options);
	}

}
