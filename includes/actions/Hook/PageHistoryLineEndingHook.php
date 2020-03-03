<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageHistoryLineEndingHook {
	/**
	 * Right before the end <li> is added to a history line.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $historyAction the action object
	 * @param ?mixed &$row the revision row for this line
	 * @param ?mixed &$s the string representing this parsed line
	 * @param ?mixed &$classes array containing the <li> element classes
	 * @param ?mixed &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryLineEnding( $historyAction, &$row, &$s, &$classes,
		&$attribs
	);
}
