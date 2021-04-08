<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;
use Status;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPage::attemptSave:after" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__attemptSave_afterHook {
	/**
	 * This hook is called after an article save attempt.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage_Obj Current EditPage object
	 * @param Status $status Resulting Status object
	 * @param array $resultDetails Result details array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__attemptSave_after( $editpage_Obj, $status,
		$resultDetails
	);
}
