<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UndeleteForm__showHistoryHook {
	/**
	 * Called in UndeleteForm::showHistory, after a
	 * PageArchive object has been created but before any further processing is done.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$archive PageArchive object
	 * @param ?mixed $title Title object of the page that we're viewing
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteForm__showHistory( &$archive, $title );
}
