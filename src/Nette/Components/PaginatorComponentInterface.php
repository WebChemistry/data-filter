<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use Nette\Application\UI\IRenderable;
use Nette\ComponentModel\IComponent;

interface PaginatorComponentInterface extends IComponent, IRenderable
{

	public function setFile(string $file): void;

	public function setAppendItemsCaption(?string $caption): void;

}
