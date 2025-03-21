<?php

namespace MediaWiki\Linker\Hook;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "HtmlPageLinkRendererBegin" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface HtmlPageLinkRendererBeginHook {
	/**
	 * This hook is called when generating internal and interwiki links in
	 * LinkRenderer, before processing starts.
	 *
	 * @since 1.35
	 *
	 * @param LinkRenderer $linkRenderer
	 * @param LinkTarget $target LinkTarget that the link is pointing to
	 * @param string|HtmlArmor|null &$text Contents that the `<a>` tag should
	 *   have; either a plain, unescaped string or an HtmlArmor object; null
	 *   means "default"
	 * @param string[] &$customAttribs HTML attributes that the `<a>` tag should have, in
	 *   associative array form, with keys and values unescaped. Should be merged
	 *   with default values, with a value of false meaning to suppress the
	 *   attribute.
	 * @param string[] &$query Query string to add to the generated URL (the bit after the "?"),
	 *   in associative array form, with keys and values unescaped.
	 * @param string &$ret Value to return if your hook returns false
	 * @return bool|void True or no return value to continue, or false to skip default
	 *   processing and return $ret
	 */
	public function onHtmlPageLinkRendererBegin( $linkRenderer, $target, &$text,
		&$customAttribs, &$query, &$ret
	);
}
