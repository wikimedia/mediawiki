<?php

namespace MediaWiki\Hook;

use ContribsPager;
use stdClass;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContributionsLineEndingHook {
	/**
	 * This hook is called before a contributions HTML line is finished
	 *
	 * @since 1.35
	 *
	 * @param ContribsPager $page SpecialPage object for contributions
	 * @param string &$ret the HTML line
	 * @param stdClass $row the DB row for this line
	 * @param array &$classes the classes to add to the surrounding <li>
	 * @param array &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContributionsLineEnding( $page, &$ret, $row, &$classes,
		&$attribs
	);
}
