<?php

namespace MediaWiki\Hook;

use SkinTemplate;
use Title;

/**
 * @deprecated since 1.35 Use SkinTemplateNavigation__Universal instead
 * @ingroup Hooks
 */
interface SkinTemplateTabActionHook {
	/**
	 * Use this hook to override SkinTemplate::tabAction().
	 * You can either create your own array, or alter the parameters for
	 * the normal one.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param Title $title
	 * @param string $message Visible label of tab
	 * @param bool $selected Whether this is a selected tab
	 * @param bool $checkEdit Whether or not the action=edit query should be added if appropriate
	 * @param string[] &$classes Array of CSS classes to apply
	 * @param string &$query Query string to add to link
	 * @param string &$text Link text
	 * @param array &$result Complete associative array if you want to return true
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateTabAction( $sktemplate, $title, $message,
		$selected, $checkEdit, &$classes, &$query, &$text, &$result
	);
}
