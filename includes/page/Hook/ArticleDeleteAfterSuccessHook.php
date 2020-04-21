<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleDeleteAfterSuccessHook {
	/**
	 * Output after an article has been deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title of the article that has been deleted.
	 * @param ?mixed $outputPage OutputPage that can be used to append the output.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDeleteAfterSuccess( $title, $outputPage );
}
