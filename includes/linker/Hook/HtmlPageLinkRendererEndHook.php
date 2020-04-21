<?php

namespace MediaWiki\Linker\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface HtmlPageLinkRendererEndHook {
	/**
	 * Used when generating internal and interwiki links in LinkRenderer,
	 * just before the function returns a value.  If you return true, an <a> element
	 * with HTML attributes $attribs and contents $html will be returned.  If you
	 * return false, $ret will be returned.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linkRenderer the LinkRenderer object
	 * @param ?mixed $target the LinkTarget object that the link is pointing to
	 * @param ?mixed $isKnown boolean indicating whether the page is known or not
	 * @param ?mixed &$text the contents that the <a> tag should have; either a plain, unescaped
	 *   string or a HtmlArmor object.
	 * @param ?mixed &$attribs the final HTML attributes of the <a> tag, after processing, in
	 *   associative array form.
	 * @param ?mixed &$ret the value to return if your hook returns false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHtmlPageLinkRendererEnd( $linkRenderer, $target, $isKnown,
		&$text, &$attribs, &$ret
	);
}
