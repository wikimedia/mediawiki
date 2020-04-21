<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageCopyrightWarningHook {
	/**
	 * Allow for site and per-namespace customization of
	 * contribution/copyright notice.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title of page being edited
	 * @param ?mixed &$msg localization message name, overridable. Default is either
	 *   'copyrightwarning' or 'copyrightwarning2'.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageCopyrightWarning( $title, &$msg );
}
