<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\PageArchive;
use MediaWiki\Title\Title;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UndeleteForm::showRevision" to register handlers implementing this interface.
 *
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
