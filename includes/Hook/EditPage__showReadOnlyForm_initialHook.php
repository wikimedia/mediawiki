<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\EditPage\EditPage;
use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPage::showReadOnlyForm:initial" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__showReadOnlyForm_initialHook {
	/**
	 * This hook is similar to EditPage::showEditForm:initial
	 * but for the read-only 'view source' variant of the edit form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor
	 * @param OutputPage $out OutputPage instance to write to
	 * @return bool|void Return value is ignored; this hook should always return true
	 */
	public function onEditPage__showReadOnlyForm_initial( $editor, $out );
}
