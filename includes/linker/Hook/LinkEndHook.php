<?php

namespace MediaWiki\Linker\Hook;

use DummyLinker;
use Title;

/**
 * @deprecated since 1.28 Use HtmlPageLinkRendererEnd hook instead
 * @ingroup Hooks
 */
interface LinkEndHook {
	/**
	 * This hook is called when generating internal and interwiki links in Linker::link(),
	 * just before the function returns a value.
	 *
	 * @since 1.35
	 *
	 * @param DummyLinker $skin Formerly a Linker/Skin, now a DummyLinker for b/c
	 * @param Title $target Title that the link is pointing to
	 * @param string[] $options Array of options, which always includes either
	 *   'known' or 'broken', and may include 'noclasses'
	 * @param string &$html Final (raw HTML) contents of the `<a>` tag, after processing
	 * @param string[] &$attribs Final HTML attributes of the `<a>` tag, after processing, in
	 *   associative array form
	 * @param string &$ret Value to return if your hook returns false
	 * @return bool|void True or no return value to continue or false to abort. If you return
	 *   true, an `<a>` element with HTML attributes $attribs and contents $html will be returned.
	 *   If you return false, $ret will be returned.
	 */
	public function onLinkEnd( $skin, $target, $options, &$html, &$attribs, &$ret );
}
