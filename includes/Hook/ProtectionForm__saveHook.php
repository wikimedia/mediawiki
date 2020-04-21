<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ProtectionForm__saveHook {
	/**
	 * Called when a protection form is submitted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the Page being (un)protected
	 * @param ?mixed &$errorMsg an html message string of an error or an array of message name and
	 *   its parameters
	 * @param ?mixed $reasonstr a string describing the reason page protection level is altered
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__save( $article, &$errorMsg, $reasonstr );
}
