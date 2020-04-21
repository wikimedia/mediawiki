<?php

namespace MediaWiki\Linker\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface HtmlPageLinkRendererBeginHook {
	/**
	 * Used when generating internal and interwiki links in
	 * LinkRenderer, before processing starts.  Return false to skip default
	 * processing and return $ret.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linkRenderer the LinkRenderer object
	 * @param ?mixed $target the LinkTarget that the link is pointing to
	 * @param ?mixed &$text the contents that the <a> tag should have; either a plain, unescaped
	 *   string or a HtmlArmor object; null means "default".
	 * @param ?mixed &$customAttribs the HTML attributes that the <a> tag should have, in
	 *   associative array form, with keys and values unescaped.  Should be merged
	 *   with default values, with a value of false meaning to suppress the
	 *   attribute.
	 * @param ?mixed &$query the query string to add to the generated URL (the bit after the "?"),
	 *   in associative array form, with keys and values unescaped.
	 * @param ?mixed &$ret the value to return if your hook returns false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHtmlPageLinkRendererBegin( $linkRenderer, $target, &$text,
		&$customAttribs, &$query, &$ret
	);
}
