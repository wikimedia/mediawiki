<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InfoActionHook {
	/**
	 * When building information to display on the action=info page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource object
	 * @param ?mixed &$pageInfo Array of information
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInfoAction( $context, &$pageInfo );
}
