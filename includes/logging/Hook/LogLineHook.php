<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LogLineHook {
	/**
	 * Use this hook to process a single log entry on Special:Log.
	 *
	 * @since 1.35
	 *
	 * @param string $log_type Type of log entry (e.g. 'move'). Corresponds to
	 *   logging.log_type database field.
	 * @param string $log_action Type of log action (e.g. 'delete', 'block',
	 *   'create2'). Corresponds to logging.log_action database field.
	 * @param Title $title Title object that corresponds to logging.log_namespace and
	 *   logging.log_title database fields
	 * @param array $paramArray Parameters that correspond to logging.log_params field.
	 *   Note that only $paramArray[0] appears to contain anything.
	 * @param string &$comment Logging.log_comment database field, which is displayed
	 *   in the UI
	 * @param string &$revert String that is displayed in the UI, similar to $comment
	 * @param string $time Timestamp of the log entry (added in 1.12)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogLine( $log_type, $log_action, $title, $paramArray,
		&$comment, &$revert, $time
	);
}
