<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Utility;

use Nette\Application\IPresenter;
use Nette\Application\UI\Control;
use WebChemistry\DataFilter\State\StateChangedEvent;

final class RedrawStateChangedEventListenerFactory
{

	private IPresenter $presenter;

	private Control $control;

	/** @var mixed[] */
	private array $snippets = [];

	public function __construct(IPresenter $presenter, ?Control $control = null)
	{
		$this->presenter = $presenter;
		$this->control = $control ?: $presenter;
	}

	public static function create(IPresenter $presenter, ?Control $control = null): self
	{
		return new self($presenter, $control);
	}

	public function redrawSnippetWhen(int $stateChangeType, string $snippet): self
	{
		$this->snippets[] = [$stateChangeType, $snippet];

		return $this;
	}

	public function redrawSnippetExcept(int $stateChangeType, string $snippet): self
	{
		$this->snippets[] = [StateChangedEvent::ALL & ~$stateChangeType, $snippet];

		return $this;
	}

	public function createListener(): callable
	{
		return function (StateChangedEvent $event): void {
			if (!$this->presenter->isAjax()) {
				return;
			}

			foreach ($this->snippets as [$stateChangeType, $snippet]) {
				if ($event->getType() & $stateChangeType) {
					$this->control->redrawControl($snippet);
				}
			}
		};
	}

}
