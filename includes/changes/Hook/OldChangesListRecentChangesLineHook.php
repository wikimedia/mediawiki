<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OldChangesListRecentChangesLineHook {
	/**
	 * Customize entire recent changes line, or
	 * return false to omit the line from RecentChanges and Watchlist special pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changeslist The OldChangesList instance.
	 * @param ?mixed &$s HTML of the form "<li>...</li>" containing one RC entry.
	 * @param ?mixed $rc The RecentChange object.
	 * @param ?mixed &$classes array of css classes for the <li> element.
	 * @param ?mixed &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOldChangesListRecentChangesLine( $changeslist, &$s, $rc,
		&$classes, &$attribs
	);
}
