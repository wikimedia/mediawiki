<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ProtectionForm__buildFormHook {
	/**
	 * This hook is called after all protection type fieldsets are made
	 * in the form.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Title being (un)protected
	 * @param string &$output String of the form HTML so far
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__buildForm( $article, &$output );
}
