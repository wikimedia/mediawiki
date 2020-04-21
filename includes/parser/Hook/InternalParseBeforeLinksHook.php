<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InternalParseBeforeLinksHook {
	/**
	 * during Parser's internalParse method before links
	 * but after nowiki/noinclude/includeonly/onlyinclude and other processings.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$text string containing partially parsed text
	 * @param ?mixed $stripState Parser's internal StripState object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInternalParseBeforeLinks( $parser, &$text, $stripState );
}
