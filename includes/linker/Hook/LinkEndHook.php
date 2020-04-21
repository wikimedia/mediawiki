<?php

namespace MediaWiki\Linker\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinkEndHook {
	/**
	 * DEPRECATED since 1.28! Use HtmlPageLinkRendererEnd hook instead
	 * Used when generating internal and interwiki links in Linker::link(),
	 * just before the function returns a value.  If you return true, an <a> element
	 * with HTML attributes $attribs and contents $html will be returned.  If you
	 * return false, $ret will be returned.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin the Skin object
	 * @param ?mixed $target the Title object that the link is pointing to
	 * @param ?mixed $options the options.  Will always include either 'known' or 'broken', and may
	 *   include 'noclasses'.
	 * @param ?mixed &$html the final (raw HTML) contents of the <a> tag, after processing.
	 * @param ?mixed &$attribs the final HTML attributes of the <a> tag, after processing, in
	 *   associative array form.
	 * @param ?mixed &$ret the value to return if your hook returns false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkEnd( $skin, $target, $options, &$html, &$attribs, &$ret );
}
