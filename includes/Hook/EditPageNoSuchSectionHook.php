<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageNoSuchSectionHook {
	/**
	 * When a section edit request is given for an
	 * non-existent section
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage The current EditPage object
	 * @param ?mixed &$res the HTML of the error text
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageNoSuchSection( $editpage, &$res );
}
