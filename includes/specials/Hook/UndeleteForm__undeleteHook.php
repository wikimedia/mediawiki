<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UndeleteForm__undeleteHook {
	/**
	 * Called in UndeleteForm::undelete, after checking that
	 * the site is not in read-only mode, that the Title object is not null and after
	 * a PageArchive object has been constructed but before performing any further
	 * processing.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$archive PageArchive object
	 * @param ?mixed $title Title object of the page that we're about to undelete
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteForm__undelete( &$archive, $title );
}
