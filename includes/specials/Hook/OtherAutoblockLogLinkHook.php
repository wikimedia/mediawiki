<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OtherAutoblockLogLinkHook {
	/**
	 * This hook is used to get links to the autoblock log
	 *
	 * Extensions may autoblocks users and/or IP addresses too.
	 *
	 * @since 1.35
	 *
	 * @param array &$otherBlockLink An array with links to other autoblock logs
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOtherAutoblockLogLink( &$otherBlockLink );
}
