<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__attemptSaveHook {
	/**
	 * Called before an article is
	 * saved, that is before WikiPage::doEditContent() is called
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage_Obj the current EditPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__attemptSave( $editpage_Obj );
}
