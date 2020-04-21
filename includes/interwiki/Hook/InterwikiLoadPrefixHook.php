<?php

namespace MediaWiki\Interwiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InterwikiLoadPrefixHook {
	/**
	 * When resolving if a given prefix is an interwiki or not.
	 * Return true without providing an interwiki to continue interwiki search.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $prefix interwiki prefix we are looking for.
	 * @param ?mixed &$iwData output array describing the interwiki with keys iw_url, iw_local,
	 *   iw_trans and optionally iw_api and iw_wikiid.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInterwikiLoadPrefix( $prefix, &$iwData );
}
