<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\EditPage\EditPage;
use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPage::showEditForm:initial" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__showEditForm_initialHook {
	/**
	 * This hook is called before showing the edit form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor
	 * @param OutputPage $out OutputPage instance to write to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showEditForm_initial( $editor, $out );
}
