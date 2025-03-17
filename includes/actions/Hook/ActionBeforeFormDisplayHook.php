<?php

namespace MediaWiki\Hook;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Page\Article;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ActionBeforeFormDisplay" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ActionBeforeFormDisplayHook {
	/**
	 * This hook is called before executing the HTMLForm object.
	 *
	 * @since 1.35
	 *
	 * @param string $name Name of the action
	 * @param HTMLForm $form
	 * @param Article $article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onActionBeforeFormDisplay( $name, $form, $article );
}
