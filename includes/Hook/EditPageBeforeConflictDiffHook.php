<?php

namespace MediaWiki\Hook;

use EditPage;
use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPageBeforeConflictDiff" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageBeforeConflictDiffHook {
	/**
	 * Use this hook to modify the EditPage object and output when there's an edit conflict.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor
	 * @param OutputPage $out
	 * @return bool|void True or no return value to continue. False to halt normal diff output;
	 *   in this case you're responsible for computing and outputting the entire "conflict" part,
	 *   i.e., the "difference between revisions" and "your text" headers and sections.
	 */
	public function onEditPageBeforeConflictDiff( $editor, $out );
}
