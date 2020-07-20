<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Template;

use Nette\Application\UI\Form;
use WebChemistry\DataFilter\Template\TemplateFacade;
use WebChemistry\DataFilter\Nette\Components\PaginatorComponentInterface;
use WebChemistry\DataFilter\DataFilter;
use WebChemistry\DataFilter\Nette\Components\DataFilterComponent;

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

	public function getResetLink(): string
	{
		return $this->component->getResetLink();
	}

}
