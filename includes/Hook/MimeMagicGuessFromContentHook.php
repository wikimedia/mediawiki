<?php

namespace MediaWiki\Hook;

use Wikimedia\Mime\MimeAnalyzer;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MimeMagicGuessFromContent" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MimeMagicGuessFromContentHook {
	/**
	 * Use this hook to guess the MIME by content.
	 *
	 * @since 1.35
	 *
	 * @param MimeAnalyzer $mimeMagic
	 * @param string &$head First 1024 bytes of the file in a string (in - Do not alter!)
	 * @param string &$tail More or equal than last 65558 bytes of the file in a string
	 *   (in - Do not alter!)
	 * @param string $file File path
	 * @param string &$mime MIME type (out)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMimeMagicGuessFromContent( $mimeMagic, &$head, &$tail, $file,
		&$mime
	);
}
