<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LogLineHook {
	/**
	 * Processes a single log entry on Special:Log.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $log_type string for the type of log entry (e.g. 'move'). Corresponds to
	 *   logging.log_type database field.
	 * @param ?mixed $log_action string for the type of log action (e.g. 'delete', 'block',
	 *   'create2'). Corresponds to logging.log_action database field.
	 * @param ?mixed $title Title object that corresponds to logging.log_namespace and
	 *   logging.log_title database fields.
	 * @param ?mixed $paramArray Array of parameters that corresponds to logging.log_params field.
	 *   Note that only $paramArray[0] appears to contain anything.
	 * @param ?mixed &$comment string that corresponds to logging.log_comment database field, and
	 *   which is displayed in the UI.
	 * @param ?mixed &$revert string that is displayed in the UI, similar to $comment.
	 * @param ?mixed $time timestamp of the log entry (added in 1.12)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogLine( $log_type, $log_action, $title, $paramArray,
		&$comment, &$revert, $time
	);
}
