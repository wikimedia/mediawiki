<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Article;
use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ProtectionForm::showLogExtract" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ProtectionForm__showLogExtractHook {
	/**
	 * This hook is called after the protection log extract is shown.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Page the form is shown for
	 * @param OutputPage $out
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__showLogExtract( $article, $out );
}
