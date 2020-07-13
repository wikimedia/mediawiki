<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__attemptSaveHook {
	/**
	 * This hook is called before an article is saved, before WikiPage::doEditContent() is called.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage_Obj Current EditPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__attemptSave( $editpage_Obj );
}
