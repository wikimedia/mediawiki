<?php

namespace MediaWiki\Hook;

use PageArchive;
use Title;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UndeleteForm__showRevisionHook {
	/**
	 * This hook is called in UndeleteForm::showRevision, after creating a PageArchive object
	 *
	 * @since 1.35
	 *
	 * @param PageArchive &$archive PageArchive object
	 * @param Title $title Title object of the page that we're viewing
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteForm__showRevision( &$archive, $title );
}
