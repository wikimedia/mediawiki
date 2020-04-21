<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ProtectionForm__buildFormHook {
	/**
	 * Called after all protection type fieldsets are made
	 * in the form.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the title being (un)protected
	 * @param ?mixed &$output a string of the form HTML so far
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onProtectionForm__buildForm( $article, &$output );
}
