<?php

namespace App\Dto;

class ListQuery
{
	public function __construct(
		public int $page = 1,
		public int $limit = 2,
	) {}

	public function getMaxResults(): int
	{
		return $this->limit;
	}

	public function getFirstResult(): int
	{
		return ($this->page - 1) * $this->limit;
	}
}