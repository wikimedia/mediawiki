<?php

namespace MediaWiki\Hook;

use ContribsPager;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContribsPager__reallyDoQueryHook {
	/**
	 * This hook is called before really executing the query for My Contributions
	 *
	 * @since 1.35
	 *
	 * @param array &$data an array of results of all contribs queries
	 * @param ContribsPager $pager The ContribsPager object hooked into
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	);
}
