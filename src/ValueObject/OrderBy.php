<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

final class OrderBy
{

	private string $id;

	private string $label;

	/** @var string[]|mixed[] */
	private array $value;

	/**
	 * @param string[]|mixed[] $value
	 */
	public function __construct(string $id, string $label, array $value)
	{
		$this->id = $id;
		$this->label = $label;
		$this->value = $value;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @return string[]|mixed[]
	 */
	public function getValue(): array
	{
		return $this->value;
	}

}
