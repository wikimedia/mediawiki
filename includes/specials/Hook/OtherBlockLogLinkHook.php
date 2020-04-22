<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OtherBlockLogLinkHook {
	/**
	 * This hook is used to get links to the block log
	 *
	 * Extensions can blocks users and/or IP addresses too.
	 *
	 * @since 1.35
	 *
	 * @param array &$otherBlockLink An array with links to other block logs
	 * @param string $ip The requested IP address or username
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOtherBlockLogLink( &$otherBlockLink, $ip );
}
