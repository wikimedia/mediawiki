<?php

namespace MediaWiki\Hook;

use MovePageForm;
use Title;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialMovepageAfterMoveHook {
	/**
	 * This hook is called after moving a page.
	 *
	 * @since 1.35
	 *
	 * @param MovePageForm $movePage MovePageForm object
	 * @param Title $oldTitle old title (object)
	 * @param Title $newTitle new title (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMovepageAfterMove( $movePage, $oldTitle, $newTitle );
}
