<?php

namespace MediaWiki\Hook;

use SpecialNewPages;
use stdClass;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface NewPagesLineEndingHook {
	/**
	 * This hook is called before a NewPages line is finished.
	 *
	 * @since 1.35
	 *
	 * @param SpecialNewPages $page the SpecialNewPages object
	 * @param string &$ret the HTML line
	 * @param stdClass $row the database row for this page (the recentchanges record and a few extras
	 *   - see NewPagesPager::getQueryInfo)
	 * @param array &$classes the classes to add to the surrounding <li>
	 * @param array &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewPagesLineEnding( $page, &$ret, $row, &$classes, &$attribs );
}
