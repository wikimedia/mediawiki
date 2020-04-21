<?php

namespace MediaWiki\Linker\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinkBeginHook {
	/**
	 * DEPRECATED since 1.28! Use HtmlPageLinkRendererBegin instead.
	 * Used when generating internal and interwiki links in Linker::link(), before
	 * processing starts.  Return false to skip default processing and return $ret. See
	 * documentation for Linker::link() for details on the expected meanings of
	 * parameters.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin the Skin object
	 * @param ?mixed $target the Title that the link is pointing to
	 * @param ?mixed &$html the contents that the <a> tag should have (raw HTML); null means
	 *   "default".
	 * @param ?mixed &$customAttribs the HTML attributes that the <a> tag should have, in
	 *   associative array form, with keys and values unescaped.  Should be merged
	 *   with default values, with a value of false meaning to suppress the
	 *   attribute.
	 * @param ?mixed &$query the query string to add to the generated URL (the bit after the "?"),
	 *   in associative array form, with keys and values unescaped.
	 * @param ?mixed &$options array of options.  Can include 'known', 'broken', 'noclasses'.
	 * @param ?mixed &$ret the value to return if your hook returns false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkBegin( $skin, $target, &$html, &$customAttribs, &$query,
		&$options, &$ret
	);
}
