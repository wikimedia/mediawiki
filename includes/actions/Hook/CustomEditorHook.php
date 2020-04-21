<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CustomEditorHook {
	/**
	 * When invoking the page editor
	 * Return true to allow the normal editor to be used, or false if implementing
	 * a custom editor, e.g. for a special namespace, etc.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article Article being edited
	 * @param ?mixed $user User performing the edit
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCustomEditor( $article, $user );
}
