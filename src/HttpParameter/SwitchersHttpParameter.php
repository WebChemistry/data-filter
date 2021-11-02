<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\HttpParameter;

use InvalidArgumentException;
use WebChemistry\DataFilter\HttpParameter\ValueObject\SwitcherCollection;
use WebChemistry\DataFilter\HttpParameter\ValueObject\SwitcherParameter;
use WebChemistry\DataFilter\ValueObject\DataFilterOptionsInterface;
use WebChemistry\DataFilter\ValueObject\Switcher;

final class SwitchersHttpParameter implements HttpParameterInterface
{

	/** @var SwitcherParameter[] */
	private array $switchers = [];

	public function __construct(DataFilterOptionsInterface $options)
	{
		foreach ($options->getSwitchers() as $switcher) {
			$this->switchers[] = new SwitcherParameter($switcher, $this->getHttpId());
		}
	}

	public function getHttpId(): string
	{
		return 'switcher_';
	}

	public function hasSwitcher(string $switcher): bool
	{
		return isset($this->switchers[$switcher]);
	}

	public function getSwitcher(string $switcher): SwitcherParameter
	{
		return $this->switchers[$switcher] ?? throw new InvalidArgumentException(sprintf('Switcher "%s" does not exist.', $switcher));
	}

	public function reset(): void
	{
		foreach ($this->switchers as $switcher) {
			$switcher->reset();
		}
	}

	public function loadState(array $params): void
	{
		foreach ($this->switchers as $switcher) {
			$switcher->loadState($params);
		}
	}

	public function saveState(array $params): array
	{
		foreach ($this->switchers as $switcher) {
			$params = $switcher->saveState($params);
		}

		return $params;
	}

}
