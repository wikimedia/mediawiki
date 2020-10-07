<?php

namespace MediaWiki\Hook;

use MovePageForm;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialMovepageAfterMoveHook {
	/**
	 * This hook is called after moving a page.
	 *
	 * @since 1.35
	 *
	 * @param MovePageForm $movePage MovePageForm object
	 * @param Title $oldTitle Old title
	 * @param Title $newTitle New title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMovepageAfterMove( $movePage, $oldTitle, $newTitle );
}
