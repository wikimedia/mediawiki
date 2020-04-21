<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContribsPager__reallyDoQueryHook {
	/**
	 * Called before really executing the query for My
	 * Contributions
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$data an array of results of all contribs queries
	 * @param ?mixed $pager The ContribsPager object hooked into
	 * @param ?mixed $offset Index offset, inclusive
	 * @param ?mixed $limit Exact query limit
	 * @param ?mixed $descending Query direction, false for ascending, true for descending
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	);
}
