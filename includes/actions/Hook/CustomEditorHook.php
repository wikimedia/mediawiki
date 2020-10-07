<?php

namespace MediaWiki\Hook;

use Article;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface CustomEditorHook {
	/**
	 * This hook is called when invoking the page editor.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Article being edited
	 * @param User $user User performing the edit
	 * @return bool|void True or no return value to allow the normal editor to be used.
	 *   False if implementing a custom editor, e.g. for a special namespace, etc.
	 */
	public function onCustomEditor( $article, $user );
}
