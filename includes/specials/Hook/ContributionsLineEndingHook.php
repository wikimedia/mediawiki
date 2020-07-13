<?php

namespace MediaWiki\Hook;

use ContribsPager;
use stdClass;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContributionsLineEndingHook {
	/**
	 * This hook is called before a contributions HTML line is finished.
	 *
	 * @since 1.35
	 *
	 * @param ContribsPager $pager The ContribsPager object hooked into
	 * @param string &$ret The HTML line
	 * @param stdClass $row The DB row for this line
	 * @param string[] &$classes The classes to add to the surrounding <li>
	 * @param string[] &$attribs Associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContributionsLineEnding( $pager, &$ret, $row, &$classes,
		&$attribs
	);
}
