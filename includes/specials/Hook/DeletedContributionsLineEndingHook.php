<?php

namespace MediaWiki\Hook;

use DeletedContribsPager;
use stdClass;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DeletedContributionsLineEndingHook {
	/**
	 * This hook is called before a DeletedContributions HTML line is finished.
	 *
	 *
	 * @see ContributionsLineEndingHook
	 *
	 * @since 1.35
	 *
	 * @param DeletedContribsPager $page Pager object for DeletedContribsPager
	 * @param string &$ret the HTML line
	 * @param stdClass $row the DB row for this line
	 * @param array &$classes the classes to add to the surrounding <li>
	 * @param array &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDeletedContributionsLineEnding( $page, &$ret, $row,
		&$classes, &$attribs
	);
}
