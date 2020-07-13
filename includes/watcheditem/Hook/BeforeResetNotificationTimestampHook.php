<?php

namespace MediaWiki\Hook;

use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeResetNotificationTimestampHook {
	/**
	 * This hook is called before the notification timestamp of a
	 * watched item is reset.
	 *
	 * @since 1.35
	 *
	 * @param User &$userObj
	 * @param Title &$titleObj
	 * @param string $force If this is the string "force", then the reset will be done even if the
	 *   page is not watched
	 * @param int &$oldid Revision ID
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeResetNotificationTimestamp( &$userObj, &$titleObj,
		$force, &$oldid
	);
}
