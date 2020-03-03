<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ProtectionForm__showLogExtractHook {
	/**
	 * Called after the protection log extract is
	 * shown.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the page the form is shown for
	 * @param ?mixed $out OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__showLogExtract( $article, $out );
}
