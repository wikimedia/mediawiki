<?php

namespace MediaWiki\Hook;

use PageArchive;
use Title;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UndeleteForm::undelete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UndeleteForm__undeleteHook {
	/**
	 * This hook is called in UndeleteForm::undelete, after checks are conducted.
	 *
	 * Called after checking that the site is not in read-only mode, that the Title object is
	 * not null and after a PageArchive object has been constructed but before performing any
	 * further processing.
	 *
	 * @since 1.35
	 *
	 * @param PageArchive &$archive
	 * @param Title $title The title of the page that we're about to undelete
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteForm__undelete( &$archive, $title );
}
