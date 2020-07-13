<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalisationCacheRecacheFallbackHook {
	/**
	 * Called for each language when merging
	 * fallback data into the cache.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $cache The LocalisationCache object
	 * @param ?mixed $code language code
	 * @param ?mixed &$alldata The localisation data from core and extensions. Note some keys may
	 *   be omitted if they won't be merged into the final result.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalisationCacheRecacheFallback( $cache, $code, &$alldata );
}
