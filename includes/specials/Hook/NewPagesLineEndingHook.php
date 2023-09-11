<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\NewPagesPager;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "NewPagesLineEnding" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface NewPagesLineEndingHook {
	/**
	 * This hook is called before a NewPages line is finished.
	 *
	 * @since 1.35
	 *
	 * @param NewPagesPager $pager
	 * @param string &$ret the HTML line
	 * @param stdClass $row The database row for this page (the recentchanges record and a few extras
	 *   - see NewPagesPager::getQueryInfo)
	 * @param string[] &$classes The classes to add to the surrounding <li>
	 * @param string[] &$attribs Associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewPagesLineEnding( $pager, &$ret, $row, &$classes, &$attribs );
}
