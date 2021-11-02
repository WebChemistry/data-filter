<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use InvalidArgumentException;
use WebChemistry\DataFilter\HttpParameter\ValueObject\FormParameter;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;

final class FormsHttpParameter implements HttpParameterInterface
{

	/** @var FormParameter[] */
	private array $forms = [];

	public function __construct(DataFilterOptionsInterface $options)
	{
		foreach ($options->getForms() as $form) {
			$this->forms[] = new FormParameter($form, $this->getHttpId());
		}
	}

	public function getHttpId(): string
	{
		return 'form_';
	}

	public function hasForm(string $form): bool
	{
		return isset($this->forms[$form]);
	}

	public function getForm(string $form): FormParameter
	{
		return $this->forms[$form] ?? throw new InvalidArgumentException(sprintf('Form "%s" does not exist.', $form));
	}

	public function reset(): void
	{
		foreach ($this->forms as $form) {
			$form->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->forms as $form) {
			$form->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->forms as $form) {
			$params = $form->saveState($params);
		}

		return $params;
	}

}
