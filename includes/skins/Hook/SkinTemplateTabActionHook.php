<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateTabActionHook {
	/**
	 * Override SkinTemplate::tabAction().
	 * You can either create your own array, or alter the parameters for
	 * the normal one.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate The SkinTemplate instance.
	 * @param ?mixed $title Title instance for the page.
	 * @param ?mixed $message Visible label of tab.
	 * @param ?mixed $selected Whether this is a selected tab.
	 * @param ?mixed $checkEdit Whether or not the action=edit query should be added if appropriate.
	 * @param ?mixed &$classes Array of CSS classes to apply.
	 * @param ?mixed &$query Query string to add to link.
	 * @param ?mixed &$text Link text.
	 * @param ?mixed &$result Complete assoc. array if you want to return true.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateTabAction( $sktemplate, $title, $message,
		$selected, $checkEdit, &$classes, &$query, &$text, &$result
	);
}
