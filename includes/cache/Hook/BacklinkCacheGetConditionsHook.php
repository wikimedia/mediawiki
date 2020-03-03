<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BacklinkCacheGetConditionsHook {
	/**
	 * Allows to set conditions for query when links to
	 * certain title are fetched.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $table table name
	 * @param ?mixed $title title of the page to which backlinks are sought
	 * @param ?mixed &$conds query conditions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBacklinkCacheGetConditions( $table, $title, &$conds );
}
