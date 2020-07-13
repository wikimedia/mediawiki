<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetLocalURL__ArticleHook {
	/**
	 * Use this hook to modify local URLs specifically pointing to article paths
	 * without any fancy queries or variants.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object of page
	 * @param string &$url String value as output (out parameter, can modify)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLocalURL__Article( $title, &$url );
}
