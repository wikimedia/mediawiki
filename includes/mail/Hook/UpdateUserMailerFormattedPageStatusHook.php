<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UpdateUserMailerFormattedPageStatusHook {
	/**
	 * Before notification email gets sent.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$formattedPageStatus list of valid page states
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUpdateUserMailerFormattedPageStatus( &$formattedPageStatus );
}
