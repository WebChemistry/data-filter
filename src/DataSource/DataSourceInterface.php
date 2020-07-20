<?php declare(strict_types = 1);

namespace WebChemistry\DataFilter\DataSource;

interface DataSourceInterface
{

	public function getItemCount(): int;

	public function getData(?int $limit = null, ?int $offset = null): iterable;

}
