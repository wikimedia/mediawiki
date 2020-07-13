<?php

namespace MediaWiki\Cache\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BacklinkCacheGetConditionsHook {
	/**
	 * Use this hook to set conditions for query when links to certain title are fetched.
	 *
	 * @since 1.35
	 *
	 * @param string $table Table name
	 * @param Title $title Title of the page to which backlinks are sought
	 * @param array &$conds Query conditions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBacklinkCacheGetConditions( $table, $title, &$conds );
}
