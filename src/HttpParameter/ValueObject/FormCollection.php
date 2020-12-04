<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter\ValueObject;

use InvalidArgumentException;
use WebChemistry\DataFilter\ValueObject\FormObject;
use WebChemistry\DataFilter\ValueObject\Link;

class FormCollection
{

	/** @var FormParameter[] */
	private array $collection = [];

	/**
	 * @param FormObject[] $forms
	 */
	public function __construct(array $forms, string $prefix)
	{
		foreach ($forms as $form) {
			$this->collection[$form->getId()] = new FormParameter($form, $prefix);
		}
	}

	public function get(string $id): FormParameter
	{
		if (!isset($this->collection[$id])) {
			throw new InvalidArgumentException(sprintf('Form %s not exists', $id));
		}

		return $this->collection[$id];
	}

	/**
	 * @return FormParameter[]
	 */
	public function all(): array
	{
		return $this->collection;
	}

	public function reset(): void
	{
		foreach ($this->collection as $linkParameter) {
			$linkParameter->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->collection as $linkParameter) {
			$linkParameter->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->collection as $linkParameter) {
			$params = $linkParameter->saveState($params);
		}

		return $params;
	}

}
