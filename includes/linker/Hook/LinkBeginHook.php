<?php

namespace MediaWiki\Linker\Hook;

use DummyLinker;
use Title;

/**
 * @deprecated since 1.28 Use HtmlPageLinkRendererBegin instead
 * @ingroup Hooks
 */
interface LinkBeginHook {
	/**
	 * This hook is called when generating internal and interwiki links in Linker::link(), before
	 * processing starts. See documentation for Linker::link() for details on the expected meanings
	 * of parameters.
	 *
	 * @since 1.35
	 *
	 * @param DummyLinker $skin Formerly a Linker/Skin, now a DummyLinker for b/c
	 * @param Title $target Title that the link is pointing to
	 * @param string|null &$html Contents that the `<a>` tag should have (raw HTML); null means
	 *   "default"
	 * @param string[] &$customAttribs HTML attributes that the `<a>` tag should have, in
	 *   associative array form, with keys and values unescaped. Should be merged
	 *   with default values, with a value of false meaning to suppress the
	 *   attribute.
	 * @param string[] &$query Query string to add to the generated URL (the bit after the "?"),
	 *   in associative array form, with keys and values unescaped
	 * @param string[] &$options Array of options, which can include 'known', 'broken', 'noclasses'
	 * @param string &$ret Value to return if your hook returns false
	 * @return bool|void True or no return value to continue, or false to skip default
	 *   processing and return $ret
	 */
	public function onLinkBegin( $skin, $target, &$html, &$customAttribs, &$query,
		&$options, &$ret
	);
}
