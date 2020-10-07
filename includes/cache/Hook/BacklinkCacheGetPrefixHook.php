<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BacklinkCacheGetPrefixHook {
	/**
	 * Use this hook to set a prefix for a specific link table.
	 *
	 * @since 1.35
	 *
	 * @param string $table Table name
	 * @param string &$prefix Prefix
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBacklinkCacheGetPrefix( $table, &$prefix );
}
