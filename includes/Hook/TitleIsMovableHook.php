<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleIsMovableHook {
	/**
	 * Called when determining if it is possible to move a page. Note
	 * that this hook is not called for interwiki pages or pages in immovable
	 * namespaces: for these, isMovable() always returns false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object that is being checked
	 * @param ?mixed &$result Boolean; whether MediaWiki currently thinks this page is movable.
	 *   Hooks may change this value to override the return value of
	 *   Title::isMovable().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleIsMovable( $title, &$result );
}
