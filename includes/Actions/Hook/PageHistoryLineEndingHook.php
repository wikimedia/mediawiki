<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\HistoryPager;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageHistoryLineEnding" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PageHistoryLineEndingHook {
	/**
	 * This hook is called right before the `<li>` is generated for a history line.
	 *
	 * @since 1.35
	 *
	 * @param HistoryPager $historyAction
	 * @param stdClass &$row The revision row for this line
	 * @param string &$s The string representing this parsed line
	 * @param string[] &$classes Array containing the `<li>` element classes
	 * @param array &$attribs Associative array of other HTML attributes for the `<li>` element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryLineEnding( $historyAction, &$row, &$s, &$classes,
		&$attribs
	);
}
