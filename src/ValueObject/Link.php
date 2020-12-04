<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

class Link
{

	private string $id;

	private string $caption;

	/** @var mixed */
	private $default;

	private bool $active;

	/**
	 * @param mixed $default
	 */
	public function __construct(string $id, string $caption, $default)
	{
		$this->id = $id;
		$this->caption = $caption;
		$this->default = $default;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getCaption(): string
	{
		return $this->caption;
	}

	/**
	 * @return mixed
	 */
	public function getDefault()
	{
		return $this->default;
	}

}
