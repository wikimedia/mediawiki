<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageBeforeConflictDiffHook {
	/**
	 * allows modifying the EditPage object and output
	 * when there's an edit conflict.  Return false to halt normal diff output; in
	 * this case you're responsible for computing and outputting the entire "conflict"
	 * part, i.e., the "difference between revisions" and "your text" headers and
	 * sections.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor EditPage instance
	 * @param ?mixed $out OutputPage instance
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageBeforeConflictDiff( $editor, $out );
}
