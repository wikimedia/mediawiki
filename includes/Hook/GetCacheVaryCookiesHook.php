<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetCacheVaryCookiesHook {
	/**
	 * Get cookies that should vary cache options.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage object
	 * @param ?mixed &$cookies array of cookies name, add a value to it if you want to add a cookie
	 *   that have to vary cache options
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetCacheVaryCookies( $out, &$cookies );
}
