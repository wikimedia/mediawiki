<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LogEventsListGetExtraInputsHook {
	/**
	 * When getting extra inputs to display on
	 * Special:Log for a specific log type
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type String of log type being displayed
	 * @param ?mixed $logEventsList LogEventsList object for context and access to the WebRequest
	 * @param ?mixed &$input string HTML of an input element (deprecated, use $formDescriptor instead)
	 * @param ?mixed &$formDescriptor array HTMLForm's form descriptor
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogEventsListGetExtraInputs( $type, $logEventsList, &$input,
		&$formDescriptor
	);
}
