<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetLinkColours" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetLinkColoursHook {
	/**
	 * Use this hook to modify the CSS class of an array of page links.
	 *
	 * @since 1.35
	 *
	 * @param string[] $linkcolour_ids Array of prefixed DB keys of the pages linked to,
	 *   indexed by page_id
	 * @param string[] &$colours (Output) Array of CSS classes, indexed by prefixed DB keys
	 * @param Title $title Title of the page being parsed, on which the links will be shown
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLinkColours( $linkcolour_ids, &$colours, $title );
}
