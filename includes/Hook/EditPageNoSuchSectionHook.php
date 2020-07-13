<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageNoSuchSectionHook {
	/**
	 * This hook is called when a section edit request is given for an non-existent section.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage Current EditPage object
	 * @param string &$res HTML of the error text
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageNoSuchSection( $editpage, &$res );
}
