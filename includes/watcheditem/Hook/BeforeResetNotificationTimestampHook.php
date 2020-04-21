<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeResetNotificationTimestampHook {
	/**
	 * Before the notification timestamp of a
	 * watched item is reset.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$userObj User object
	 * @param ?mixed &$titleObj Title object
	 * @param ?mixed $force If this is the string "force", then the reset will be done even if the
	 *   page is not watched.
	 * @param ?mixed &$oldid The revision ID
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeResetNotificationTimestamp( &$userObj, &$titleObj,
		$force, &$oldid
	);
}
