<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use Nette\Application\UI\IRenderable;
use Nette\ComponentModel\IComponent;

interface PaginatorComponentInterface extends IComponent, IRenderable
{

	public function setTemplate(string $template): void;

	public function setTemplateFile(string $file, string $template = 'default'): void;

	public function setAppendItemsCaption(?string $caption): void;

	public function setPrependItemsCaption(?string $caption): void;

}
