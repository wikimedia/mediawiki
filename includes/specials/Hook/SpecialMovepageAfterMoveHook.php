<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialMovepageAfterMoveHook {
	/**
	 * Called after moving a page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $movePage MovePageForm object
	 * @param ?mixed $oldTitle old title (object)
	 * @param ?mixed $newTitle new title (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMovepageAfterMove( $movePage, $oldTitle, $newTitle );
}
