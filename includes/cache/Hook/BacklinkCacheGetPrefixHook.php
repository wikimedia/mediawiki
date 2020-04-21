<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BacklinkCacheGetPrefixHook {
	/**
	 * Allows to set prefix for a specific link table.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $table table name
	 * @param ?mixed &$prefix prefix
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBacklinkCacheGetPrefix( $table, &$prefix );
}
