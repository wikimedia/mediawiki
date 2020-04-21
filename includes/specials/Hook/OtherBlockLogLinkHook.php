<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OtherBlockLogLinkHook {
	/**
	 * Get links to the block log from extensions which blocks
	 * users and/or IP addresses too.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$otherBlockLink An array with links to other block logs
	 * @param ?mixed $ip The requested IP address or username
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOtherBlockLogLink( &$otherBlockLink, $ip );
}
