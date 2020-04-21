<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageHistoryPager__doBatchLookupsHook {
	/**
	 * Called after the pager query was run, before
	 * any output is generated, to allow batch lookups for prefetching information
	 * needed for display. If the hook handler returns false, the regular behavior of
	 * doBatchLookups() is skipped.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $pager the PageHistoryPager
	 * @param ?mixed $result a ResultWrapper representing the query result
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryPager__doBatchLookups( $pager, $result );
}
