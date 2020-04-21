<?php

namespace MediaWiki\Hook;

use HTMLForm;
use WikiPage;

/**
 * @stable for implementation
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
	 * @param WikiPage $article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onActionBeforeFormDisplay( $name, $form, $article );
}
