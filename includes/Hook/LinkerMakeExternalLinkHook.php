<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinkerMakeExternalLinkHook {
	/**
	 * At the end of Linker::makeExternalLink() just
	 * before the return.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$url the link url
	 * @param ?mixed &$text the link text
	 * @param ?mixed &$link the new link HTML (if returning false)
	 * @param ?mixed &$attribs the attributes to be applied.
	 * @param ?mixed $linkType The external link type
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerMakeExternalLink( &$url, &$text, &$link, &$attribs,
		$linkType
	);
}
