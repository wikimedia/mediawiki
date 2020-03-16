<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageCopyrightWarningHook {
	/**
	 * Use this hook for site and per-namespace customization of contribution/copyright notice.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of page being edited
	 * @param string &$msg Localization message name, overridable. Default is either
	 *   'copyrightwarning' or 'copyrightwarning2'.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageCopyrightWarning( $title, &$msg );
}
