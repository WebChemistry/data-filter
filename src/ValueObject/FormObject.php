<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use Nette\Application\UI\Form;

final class FormObject
{

	private Form $form;

	/** @var mixed[] */
	private array $defaults;

	private string $id;

	public function __construct(string $id, Form $form)
	{
		$this->id = $id;
		$this->form = $form;
		$this->defaults = $this->getDefaultValues();
	}

	private function getDefaultValues(): array
	{
		if (method_exists($this->form, 'getUnsafeValues')) {
			return $this->form->getUnsafeValues('array');
		} else {
			return $this->form->getValues('array');
		}
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getForm(): Form
	{
		return $this->form;
	}

	/**
	 * @return mixed[]
	 */
	public function getDefaults(): array
	{
		return $this->defaults;
	}

}
