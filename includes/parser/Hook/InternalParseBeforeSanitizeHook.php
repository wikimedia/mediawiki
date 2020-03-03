<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InternalParseBeforeSanitizeHook {
	/**
	 * during Parser's internalParse method just before
	 * the parser removes unwanted/dangerous HTML tags and after nowiki/noinclude/
	 * includeonly/onlyinclude and other processings. Ideal for syntax-extensions after
	 * template/parser function execution which respect nowiki and HTML-comments.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$text string containing partially parsed text
	 * @param ?mixed $stripState Parser's internal StripState object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInternalParseBeforeSanitize( $parser, &$text, $stripState );
}
