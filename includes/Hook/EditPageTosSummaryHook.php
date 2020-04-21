<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageTosSummaryHook {
	/**
	 * Give a chance for site and per-namespace customizations
	 * of terms of service summary link that might exist separately from the copyright
	 * notice.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title of page being edited
	 * @param ?mixed &$msg localization message name, overridable. Default is 'editpage-tos-summary'
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageTosSummary( $title, &$msg );
}
