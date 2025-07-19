<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UpdateUserMailerFormattedPageStatus" to register handlers implementing this interface.
 *
 * @deprecated since 1.45, use the Notification Middleware to filter/replace Watchlist notifications
 * @ingroup Hooks
 */
interface UpdateUserMailerFormattedPageStatusHook {
	/**
	 * This hook is called before a notification email gets sent.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$formattedPageStatus List of valid page states
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUpdateUserMailerFormattedPageStatus( &$formattedPageStatus );
}
