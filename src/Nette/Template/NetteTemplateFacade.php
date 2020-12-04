<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Template;

use Nette\Application\UI\Form;
use WebChemistry\DataFilter\Nette\Components\ValueObject\LinkTemplate;
use WebChemistry\DataFilter\Nette\Components\ValueObject\OrderByTemplate;
use WebChemistry\DataFilter\Nette\Components\ValueObject\SwitcherTemplate;
use WebChemistry\DataFilter\Template\TemplateFacade;
use WebChemistry\DataFilter\Nette\Components\PaginatorComponentInterface;
use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\Nette\Components\DataFilterComponent;
use WebChemistry\DataFilter\ValueObject\OrderBy;

final class NetteTemplateFacade extends TemplateFacade
{

	private DataFilterComponent $component;

	public function __construct(DataFilter $dataFilter, DataFilterComponent $component)
	{
		parent::__construct($dataFilter);

		$this->component = $component;
	}

	public function getPaginator(): PaginatorComponentInterface
	{
		return $this->component->getPaginatorComponent();
	}

	public function getSearchForm(): Form
	{
		$component = $this->component['search'];
		assert($component instanceof Form);

		return $component;
	}

	public function getOrderByForm(bool $onChangeSubmit = false): Form
	{
		$component = $this->component['orderBy'];
		assert($component instanceof Form);

		if ($onChangeSubmit && isset($component['send'])) {
			$component['orderBy']->setHtmlAttribute('onchange', 'this.form.submit()');
			unset($component['send']);
		}

		return $component;
	}

	public function getOrderBy(string $id): OrderByTemplate
	{
		return $this->component->getOrderBy($id);
	}

	/**
	 * @return OrderByTemplate[]
	 */
	public function getOrderByMany(): array
	{
		$list = [];
		foreach ($this->dataFilter->getOptions()->getOrderByList() as $orderBy) {
			$list[] = $this->component->getOrderBy($orderBy->getId());
		}

		return $list;
	}

	public function getLink(string $id, $value): LinkTemplate
	{
		return $this->component->getLink($id, $value);
	}

	public function getSwitcher(string $id): SwitcherTemplate
	{
		return $this->component->getSwitcher($id);
	}

	/**
	 * @return SwitcherTemplate[]
	 */
	public function getSwitchers(): array
	{
		return $this->component->getSwitchers();
	}

	/**
	 * @return SwitcherTemplate[]
	 */
	public function getSwitchersByCategory(?string $category): array
	{
		$array = [];
		foreach ($this->getSwitchers() as $switcher) {
			if ($switcher->getCategory() === $category) {
				$array[] = $switcher;
			}
		}

		return $array;
	}

	public function getForm(string $id): Form
	{
		return $this->component->getForm($id);
	}

	public function getResetLink(): string
	{
		return $this->component->getResetLink();
	}

}
