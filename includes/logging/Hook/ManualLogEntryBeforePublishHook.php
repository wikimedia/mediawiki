<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ManualLogEntryBeforePublishHook {
	/**
	 * Allows to access or modify log entry just before it is
	 * published.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $logEntry ManualLogEntry object
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onManualLogEntryBeforePublish( $logEntry );
}
