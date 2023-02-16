<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\EditPage\EditPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPage::attemptSave" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__attemptSaveHook {
	/**
	 * This hook is called before an article is saved, before WikiPage::doUserEditContent() is called.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage_Obj Current EditPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__attemptSave( $editpage_Obj );
}
