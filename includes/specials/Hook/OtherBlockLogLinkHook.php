<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OtherBlockLogLink" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OtherBlockLogLinkHook {
	/**
	 * Use this hook to add list items to a list of "other blocks" when
	 * viewing Special:BlockList. Handlers should append HTML fragments to
	 * the $otherBlockLink array.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$otherBlockLink Array of HTML fragments
	 * @param string $ip The requested IP address or username, or an empty
	 *   string if Special:BlockList is showing all blocks.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOtherBlockLogLink( &$otherBlockLink, $ip );
}
