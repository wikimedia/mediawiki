<?php

namespace MediaWiki\Hook;

use ManualLogEntry;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ManualLogEntryBeforePublishHook {
	/**
	 * Use this hook to access or modify log entry just before it is
	 * published.
	 *
	 * @since 1.35
	 *
	 * @param ManualLogEntry $logEntry
	 * @return bool|void This hook must not abort; it must return true or no return value
	 */
	public function onManualLogEntryBeforePublish( $logEntry );
}
