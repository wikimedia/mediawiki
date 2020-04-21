<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLinkColoursHook {
	/**
	 * modify the CSS class of an array of page links.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linkcolour_ids array of prefixed DB keys of the pages linked to,
	 *   indexed by page_id.
	 * @param ?mixed &$colours (output) array of CSS classes, indexed by prefixed DB keys
	 * @param ?mixed $title Title object of the page being parsed, on which the links will be shown
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLinkColours( $linkcolour_ids, &$colours, $title );
}
