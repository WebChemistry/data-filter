<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class DoctrineDataSource implements DataSourceInterface
{

	private QueryBuilder $queryBuilder;

	/** @var mixed[] */
	private array $options;

	private Paginator $paginator;

	private bool $compositeId;

	private int $itemCount;

	public function __construct(QueryBuilder $queryBuilder, array $options = [])
	{
		$this->queryBuilder = $queryBuilder;
		$this->options = array_merge([
			'hydrationMode' => AbstractQuery::HYDRATE_OBJECT,
			'outputWalkers' => true,
		], $options);
	}

	public function getItemCount(): int {
		if (!isset($this->itemCount)) {
			$this->itemCount = $this->getPaginator()->count();
		}

		return $this->itemCount;
	}

	public function getData(?int $limit = null, ?int $offset = null): iterable {
		$query = $this->getPaginator()->getQuery();
		$query->setMaxResults($limit);
		$query->setFirstResult($offset);

		return $this->getPaginator()->getIterator();
	}

	protected function getPaginator(): Paginator {
		if (!isset($this->paginator)) {
			$query = $this->queryBuilder->getQuery()
				->setHydrationMode($this->options['hydrationMode']);

			$this->paginator = new Paginator($query, !$this->isCompositeId());
			$this->paginator->setUseOutputWalkers($this->options['outputWalkers']);
		}

		return $this->paginator;
	}

	protected function isCompositeId(): bool {
		if (!isset($this->compositeId)) {
			$this->compositeId = false;
			foreach ($this->queryBuilder->getRootEntities() as $entity) {
				if ($this->queryBuilder->getEntityManager()->getClassMetadata($entity)->isIdentifierComposite) {
					$this->compositeId = true;

					break;
				}
			}
		}

		return $this->compositeId;
	}

}
