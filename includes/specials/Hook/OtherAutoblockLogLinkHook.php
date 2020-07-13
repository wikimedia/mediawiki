<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OtherAutoblockLogLinkHook {
	/**
	 * Use this hook to add list items to a list of "other autoblocks" which
	 * appears at the end of Special:AutoblockList. Handlers should append
	 * HTML fragments to the $otherBlockLink array.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$otherBlockLink An array of HTML fragments
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOtherAutoblockLogLink( &$otherBlockLink );
}
