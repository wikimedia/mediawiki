<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface NewPagesLineEndingHook {
	/**
	 * Called before a NewPages line is finished.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page the SpecialNewPages object
	 * @param ?mixed &$ret the HTML line
	 * @param ?mixed $row the database row for this page (the recentchanges record and a few extras
	 *   - see NewPagesPager::getQueryInfo)
	 * @param ?mixed &$classes the classes to add to the surrounding <li>
	 * @param ?mixed &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewPagesLineEnding( $page, &$ret, $row, &$classes, &$attribs );
}
