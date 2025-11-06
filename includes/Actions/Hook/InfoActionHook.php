<?php

namespace MediaWiki\Hook;

use MediaWiki\Context\IContextSource;
use MediaWiki\Message\Message;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InfoAction" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface InfoActionHook {
	/**
	 * This hook is called when building information to display on the action=info page.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context
	 * @param array &$pageInfo Array of information, see InfoAction::pageInfo()
	 * @phan-param array<string, list<array{0:string|Message, 1:string|Message}>> &$pageInfo
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInfoAction( $context, &$pageInfo );
}
