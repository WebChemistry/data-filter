<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use WebChemistry\DataFilter\ValueObject\FormObject;

final class FormParameter
{

	private FormObject $formObject;

	private string $prefix;

	/** @var mixed[] */
	private array $values;

	public function __construct(FormObject $formObject, string $prefix)
	{
		$this->formObject = $formObject;
		$this->prefix = $prefix;

		$this->reset();
	}

	public function getHttpId(string $input): string
	{
		return $this->prefix . $this->formObject->getId() . '_' . $input;
	}

	public function reset(): void
	{
		$this->values = $this->formObject->getDefaults();
	}

	public function getValue()
	{
		return $this->values;
	}

	public function setValue(array $value): void
	{
		$this->values = $value;
	}

	public function loadState(array $params): void
	{
		foreach ($this->formObject->getForm()->getControls() as $control) {
			$name = $control->getName();
			$id = $this->getHttpId($name);

			if (isset($params[$id])) {
				$this->values[$name] = $params[$id];
			}
		}
	}

	public function saveState(array $params): array
	{
		if (!$this->isDefault()) {
			foreach ($this->formObject->getForm()->getControls() as $control) {
				$name = $control->getName();
				if (isset($this->values[$name])) {
					$params[$this->getHttpId($name)] = $this->values[$name];
				}
			}
		}

		return $params;
	}

	public function isDefault(): bool
	{
		return $this->values === $this->formObject->getDefaults();
	}

}
