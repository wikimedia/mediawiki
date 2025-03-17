<?php

namespace MediaWiki\Hook;

use MediaWiki\Logging\LogEventsList;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LogEventsListGetExtraInputs" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LogEventsListGetExtraInputsHook {
	/**
	 * This hook is called when getting extra inputs to display on
	 * Special:Log for a specific log type.
	 *
	 * @since 1.35
	 *
	 * @param string $type Log type being displayed
	 * @param LogEventsList $logEventsList LogEventsList object for context
	 *   and access to the WebRequest
	 * @param string &$unused
	 * @param array &$formDescriptor HTMLForm's form descriptor
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogEventsListGetExtraInputs( $type, $logEventsList, &$unused,
		&$formDescriptor
	);
}
