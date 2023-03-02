<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPageTosSummary" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageTosSummaryHook {
	/**
	 * Use this hook for site and per-namespace customizations of terms of service summary link
	 * that might exist separately from the copyright notice.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of page being edited
	 * @param string &$msg Localization message name, overridable. Defaults to 'editpage-tos-summary'
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageTosSummary( $title, &$msg );
}
