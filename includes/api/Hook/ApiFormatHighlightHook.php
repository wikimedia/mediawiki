<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiFormatHighlightHook {
	/**
	 * Use to syntax-highlight API pretty-printed output. When
	 * highlighting, add output to $context->getOutput() and return false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context An IContextSource.
	 * @param ?mixed $text Text to be highlighted.
	 * @param ?mixed $mime MIME type of $text.
	 * @param ?mixed $format API format code for $text.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiFormatHighlight( $context, $text, $mime, $format );
}
