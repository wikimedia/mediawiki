<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FormatAutocommentsHook {
	/**
	 * When an autocomment is formatted by the Linker.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$comment Reference to the accumulated comment. Initially null, when set the
	 *   default code will be skipped.
	 * @param ?mixed $pre Boolean, true if there is text before this autocomment
	 * @param ?mixed $auto The extracted part of the parsed comment before the call to the hook.
	 * @param ?mixed $post Boolean, true if there is text after this autocomment
	 * @param ?mixed $title An optional title object used to links to sections. Can be null.
	 * @param ?mixed $local Boolean indicating whether section links should refer to local page.
	 * @param ?mixed $wikiId String containing the ID (as used by WikiMap) of the wiki from which the
	 *   autocomment originated; null for the local wiki. Added in 1.26, should default
	 *   to null in handler functions, for backwards compatibility.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFormatAutocomments( &$comment, $pre, $auto, $post, $title,
		$local, $wikiId
	);
}
