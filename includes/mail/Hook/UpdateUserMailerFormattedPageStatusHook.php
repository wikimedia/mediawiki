<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
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
