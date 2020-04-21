<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__attemptSave_afterHook {
	/**
	 * Called after an article save attempt
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage_Obj the current EditPage object
	 * @param ?mixed $status the resulting Status object
	 * @param ?mixed $resultDetails Result details array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__attemptSave_after( $editpage_Obj, $status,
		$resultDetails
	);
}
