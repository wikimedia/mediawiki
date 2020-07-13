<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ProtectionForm__saveHook {
	/**
	 * This hook is called when a protection form is submitted.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Page being (un)protected
	 * @param string|array &$errorMsg HTML message string of an error or an array of message name and
	 *   its parameters
	 * @param string $reasonstr String describing the reason page protection level is altered
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__save( $article, &$errorMsg, $reasonstr );
}
