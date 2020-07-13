<?php

namespace MediaWiki\Hook;

use ManualLogEntry;

/**
 * @stable to implement
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
	 * @return void This hook must not abort, it must return no value
	 */
	public function onManualLogEntryBeforePublish( $logEntry ) : void;
}
