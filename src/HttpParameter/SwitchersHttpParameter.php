<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use WebChemistry\DataFilter\HttpParameter\ValueObject\SwitcherCollection;
use WebChemistry\DataFilter\ValueObject\Switcher;

final class SwitchersHttpParameter implements HttpParameterInterface
{

	private SwitcherCollection $value;

	/**
	 * @param Switcher[] $switchers
	 */
	public function __construct(array $switchers)
	{
		$this->value = new SwitcherCollection($switchers, 'switcher_');
	}

	public function getValue(): SwitcherCollection
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
