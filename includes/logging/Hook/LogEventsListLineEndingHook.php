<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LogEventsListLineEndingHook {
	/**
	 * Called before a Special:Log line is finished
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page the LogEventsList object
	 * @param ?mixed &$ret the HTML line
	 * @param ?mixed $entry the DatabaseLogEntry object for this row
	 * @param ?mixed &$classes the classes to add to the surrounding <li>
	 * @param ?mixed &$attribs associative array of other HTML attributes for the <li> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogEventsListLineEnding( $page, &$ret, $entry, &$classes,
		&$attribs
	);
}
