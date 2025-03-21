<?php

namespace MediaWiki\Linker\Hook;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "HtmlPageLinkRendererEnd" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface HtmlPageLinkRendererEndHook {
	/**
	 * This hook is called when generating internal and interwiki links in LinkRenderer,
	 * just before the function returns a value.
	 *
	 * @since 1.35
	 *
	 * @param LinkRenderer $linkRenderer
	 * @param LinkTarget $target LinkTarget object that the link is pointing to
	 * @param bool $isKnown Whether the page is known or not
	 * @param string|HtmlArmor &$text Contents that the `<a>` tag should have; either a plain,
	 *   unescaped string or an HtmlArmor object
	 * @param string[] &$attribs Final HTML attributes of the `<a>` tag, after processing, in
	 *   associative array form
	 * @param string &$ret Value to return if your hook returns false
	 * @return bool|void True or no return value to continue or false to abort. If you return
	 *   true, an `<a>` element with HTML attributes $attribs and contents $html will be
	 *   returned. If you return false, $ret will be returned.
	 */
	public function onHtmlPageLinkRendererEnd( $linkRenderer, $target, $isKnown,
		&$text, &$attribs, &$ret
	);
}
