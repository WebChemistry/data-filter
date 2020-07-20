<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use Nette\Schema\Expect;
use Nette\Schema\Processor;

final class DataFilterOptions implements DataFilterOptionsInterface
{

	/** @var mixed[] */
	private array $options;

	public function __construct(array $options)
	{
		$this->options = $this->parseOptions($options);
	}

	public function getLimit(): ?int
	{
		return $this->options['limit'];
	}

	public function isAjax(): bool
	{
		return $this->options['ajax'];
	}

	public function getLimits(): array
	{
		return $this->options['limits'];
	}

	public function getOrderByList(): array
	{
		return $this->options['orderBy'];
	}

	public function getDefaultOrderBy(): ?OrderBy
	{
		return $this->options['defaultOrderBy'];
	}

	/**
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	private function parseOptions(array $values): array
	{
		$schema = Expect::structure([
			'ajax' => Expect::bool(false),
			'limit' => Expect::int(null)->min(1),
			'orderBy' => Expect::arrayOf(Expect::type(OrderBy::class)),
			'defaultOrderBy' => Expect::type(OrderBy::class)->nullable()->default(null),
			'limits' => Expect::arrayOf('int'),
		])->castTo('array');

		return (new Processor())->process($schema, $values);
	}

}
