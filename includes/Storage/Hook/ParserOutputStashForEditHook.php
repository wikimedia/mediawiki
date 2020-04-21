<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserOutputStashForEditHook {
	/**
	 * Called when an edit stash parse finishes, before the
	 * output is cached.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page the WikiPage of the candidate edit
	 * @param ?mixed $content the Content object of the candidate edit
	 * @param ?mixed $output the ParserOutput result of the candidate edit
	 * @param ?mixed $summary the change summary of the candidate edit
	 * @param ?mixed $user the User considering the edit
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserOutputStashForEdit( $page, $content, $output, $summary,
		$user
	);
}
