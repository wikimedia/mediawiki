<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPageCopyrightWarning" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageCopyrightWarningHook {
	/**
	 * Use this hook for site and per-namespace customization of contribution/copyright notice.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of page being edited
	 * @param array &$msg An array of arguments to wfMessage(), overridable.
	 *   The default is an array containing either 'copyrightwarning' or
	 *   'copyrightwarning2' as the first element (the message key).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageCopyrightWarning( $title, &$msg );
}
