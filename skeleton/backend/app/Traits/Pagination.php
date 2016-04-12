<?php

namespace App\Traits;

trait Pagination
{
	/**
	 * @param $query
	 *
	 * @return string
	 */
	public static function addPaginationToQuery($query)
	{
		$query .= " LIMIT :page, :limit";

		return $query;
	}

	/**
	 * @param $page
	 * @param $limit
	 * @param int $defaultLimit
	 *
	 * @return array
	 */
	public static function toPaginationParameters($page, $limit, $defaultLimit = 10)
	{
		$limit = !isset($limit) ? $defaultLimit : intval($limit);
		$page = !isset($page) ? 0 : (intval($page) - 1) * $limit;

		return ['page' => $page, 'limit' => $limit];
	}
}
