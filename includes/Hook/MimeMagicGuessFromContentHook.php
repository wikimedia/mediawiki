<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MimeMagicGuessFromContentHook {
	/**
	 * Allows MW extensions guess the MIME by content.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $mimeMagic Instance of MimeAnalyzer.
	 * @param ?mixed &$head First 1024 bytes of the file in a string (in - Do not alter!).
	 * @param ?mixed &$tail More or equal than last 65558 bytes of the file in a string
	 *   (in - Do not alter!).
	 * @param ?mixed $file File path.
	 * @param ?mixed &$mime MIME type (out).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMimeMagicGuessFromContent( $mimeMagic, &$head, &$tail, $file,
		&$mime
	);
}
