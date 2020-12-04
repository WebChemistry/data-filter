<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\ValueObject\FormCollection;

final class FormsHttpParameter implements HttpParameterInterface
{

	private FormCollection $value;

	/**
	 * @param Link[] $links
	 */
	public function __construct(array $links)
	{
		$this->value = new FormCollection($links, 'form_');
	}

	public function getValue(): FormCollection
	{
		return $this->value;
	}

	public function reset(): void
	{
		$this->value->reset();
	}

	public function loadState(array $params): void
	{
		$this->value->loadState($params);
	}

	public function saveState(array $params): array
	{
		return $this->value->saveState($params);
	}

}
