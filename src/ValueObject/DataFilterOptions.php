<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\ValueObject;

use InvalidArgumentException;
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

	public function getOrderBy(string $id): OrderBy
	{
		if (!isset($this->options['orderBy'][$id])) {
			throw new InvalidArgumentException(sprintf('Order by with id %s not exists', $id));
		}

		return $this->options['orderBy'][$id];
	}

	public function getDefaultOrderBy(): ?OrderBy
	{
		return $this->options['defaultOrderBy'];
	}

	public function getLinks(): array
	{
		return $this->options['links'];
	}

	/**
	 * @return Switcher[]
	 */
	public function getSwitchers(): array
	{
		return $this->options['switchers'];
	}

	public function getForms(): array
	{
		return $this->options['forms'];
	}

	public function isEnabledLinkEvents(): bool
	{
		return $this->options['enabledLinkEvents'];
	}

	/**
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	private function parseOptions(array $values): array
	{
		$schema = Expect::structure([
			'ajax' => Expect::bool(false),
			'enabledLinkEvents' => Expect::bool(false),
			'limit' => Expect::int(null)->min(1)->nullable(),
			'orderBy' => Expect::arrayOf(Expect::type(OrderBy::class)),
			'defaultOrderBy' => Expect::type(OrderBy::class)->nullable()->default(null),
			'limits' => Expect::arrayOf('int'),
			'links' => Expect::arrayOf(Expect::type(Link::class)),
			'switchers' => Expect::arrayOf(Expect::type(Switcher::class)),
			'forms' => Expect::arrayOf(Expect::type(FormObject::class)),
		])->castTo('array');

		return (new Processor())->process($schema, $values);
	}

}
