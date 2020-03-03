<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLocalURL__ArticleHook {
	/**
	 * Modify local URLs specifically pointing to article paths
	 * without any fancy queries or variants.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of page
	 * @param ?mixed &$url string value as output (out parameter, can modify)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLocalURL__Article( $title, &$url );
}
