<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLinkColoursHook {
	/**
	 * Use this hook to modify the CSS class of an array of page links.
	 *
	 * @since 1.35
	 *
	 * @param array $linkcolour_ids Array of prefixed DB keys of the pages linked to,
	 *   indexed by page_id
	 * @param array &$colours (Output) Array of CSS classes, indexed by prefixed DB keys
	 * @param Title $title Title of the page being parsed, on which the links will be shown
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLinkColours( $linkcolour_ids, &$colours, $title );
}
