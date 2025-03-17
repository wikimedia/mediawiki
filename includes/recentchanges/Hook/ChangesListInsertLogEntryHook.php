<?php

namespace MediaWiki\Hook;

use MediaWiki\Context\IContextSource;
use MediaWiki\Logging\DatabaseLogEntry;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangesListInsertLogEntry" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangesListInsertLogEntryHook {
	/**
	 * Use this hook to override or modify the line for a log entry in a RC list
	 *
	 * @since 1.44
	 *
	 * @param DatabaseLogEntry $entry The log entry object for this line
	 * @param IContextSource $context The context associated with the RC list
	 * @param string &$html The HTML that has been generated for the log entry, not yet wrapped
	 *   inside a wrapping span element that has the specified $classes and $attribs.
	 * @param array &$classes The CSS classes to be added to a span element that will
	 *   wrap the $html
	 * @param array &$attribs HTML attributes to be added to a span element that will
	 *   wrap the $html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInsertLogEntry( $entry, $context, string &$html, array &$classes, array &$attribs );
}
