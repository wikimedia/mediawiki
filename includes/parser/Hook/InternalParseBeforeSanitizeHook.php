<?php

namespace MediaWiki\Hook;

use Parser;
use StripState;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface InternalParseBeforeSanitizeHook {
	/**
	 * This hook is called during Parser's internalParse method just before
	 * the parser removes unwanted/dangerous HTML tags and after nowiki/noinclude/
	 * includeonly/onlyinclude and other processing. Ideal for syntax-extensions after
	 * template/parser function execution which respect nowiki and HTML-comments.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$text Partially parsed text
	 * @param StripState $stripState Parser's internal StripState object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInternalParseBeforeSanitize( $parser, &$text, $stripState );
}
