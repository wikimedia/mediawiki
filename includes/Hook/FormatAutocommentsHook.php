<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface FormatAutocommentsHook {
	/**
	 * This hook is called when an autocomment is formatted by the Linker.
	 *
	 * @since 1.35
	 *
	 * @param string|null &$comment Reference to the accumulated comment.
	 *   Initially null, when set the default code will be skipped.
	 * @param bool $pre True if there is text before this autocomment
	 * @param string $auto Extracted part of the parsed comment before the call to the hook
	 * @param bool $post True if there is text after this autocomment
	 * @param Title|null $title Optional title object used to links to sections
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId ID (as used by WikiMap) of the wiki from which the
	 *   autocomment originated; null for the local wiki. Added in 1.26, should default
	 *   to null in handler functions, for backwards compatibility.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFormatAutocomments( &$comment, $pre, $auto, $post, $title,
		$local, $wikiId
	);
}
