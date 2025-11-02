<?php

namespace MediaWiki\Interwiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InterwikiLoadPrefix" to register handlers implementing this interface.
 *
 * @deprecated since 1.36, does not support enumerating all valid prefixes; for testing
 *    purposes (re)set $wgInterwikiCache instead.
 * @stable to implement
 * @ingroup Hooks
 */
interface InterwikiLoadPrefixHook {
	/**
	 * This hook is called when resolving whether a given prefix is an interwiki or not.
	 *
	 * @since 1.35
	 *
	 * @param string $prefix Interwiki prefix we are looking for
	 * @param array &$iwData Output array describing the interwiki with keys iw_url, iw_local,
	 *   iw_trans and optionally iw_api and iw_wikiid
	 * @return bool|void True (or no return value) without providing an interwiki to continue
	 *   interwiki search, or false to abort
	 */
	public function onInterwikiLoadPrefix( $prefix, &$iwData );
}
