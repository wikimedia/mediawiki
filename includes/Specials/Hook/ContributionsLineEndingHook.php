<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\ContributionsPager;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ContributionsLineEnding" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ContributionsLineEndingHook {
	/**
	 * This hook is called before a contributions HTML line is finished.
	 *
	 * @since 1.35
	 *
	 * @param ContributionsPager $pager The pager object hooked into
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
