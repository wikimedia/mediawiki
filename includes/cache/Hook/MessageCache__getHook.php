<?php

namespace MediaWiki\Cache\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MessageCache__getHook {
	/**
	 * When fetching a message. Can be used to override the key
	 * for customisations. Given and returned message key must be in special format:
	 * 1) first letter must be in lower case according to the content language.
	 * 2) spaces must be replaced with underscores
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$key message key (string)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessageCache__get( &$key );
}
