<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\ContributionsPager;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DeletedContributionsLineEnding" to register handlers implementing this interface.
 *
 * @stable to implement
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
	 * @param ContributionsPager $page Pager object for ContributionsPager
	 * @param string &$ret The HTML line
	 * @param stdClass $row The DB row for this line
	 * @param string[] &$classes The classes to add to the surrounding <li>
	 * @param string[] &$attribs Associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDeletedContributionsLineEnding( $page, &$ret, $row,
		&$classes, &$attribs
	);
}
