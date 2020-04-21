<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ActionBeforeFormDisplayHook {
	/**
	 * Before executing the HTMLForm object.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name name of the action
	 * @param ?mixed $form HTMLForm object
	 * @param ?mixed $article Article object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onActionBeforeFormDisplay( $name, $form, $article );
}
