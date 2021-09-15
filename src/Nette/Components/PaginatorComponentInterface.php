<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\Nette\Components;

use Nette\Application\UI\IRenderable;
use Nette\ComponentModel\IComponent;

interface PaginatorComponentInterface extends IComponent, IRenderable
{

	public function setTemplate(string $template): PaginatorComponentInterface;

	public function setTemplateFile(string $file, string $template = 'default'): PaginatorComponentInterface;

	public function setAppendItemsFile(string $appendItemsFile): PaginatorComponentInterface;

	public function setAppendItemsCaption(?string $caption): PaginatorComponentInterface;

	public function setPrependItemsCaption(?string $caption): PaginatorComponentInterface;

	public function setInfiniteScrollAttribute(bool $infiniteScrollAttribute = true): PaginatorComponentInterface;

}
