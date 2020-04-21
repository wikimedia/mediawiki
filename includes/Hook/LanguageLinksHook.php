<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LanguageLinksHook {
	/**
	 * Manipulate a page's language links. This is called
	 * in various places to allow extensions to define the effective language
	 * links for a page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The page's Title.
	 * @param ?mixed &$links Array with elements of the form "language:title" in the order
	 *   that they will be output.
	 * @param ?mixed &$linkFlags Associative array mapping prefixed links to arrays of flags.
	 *   Currently unused, but planned to provide support for marking individual
	 *   language links in the UI, e.g. for featured articles.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageLinks( $title, &$links, &$linkFlags );
}
