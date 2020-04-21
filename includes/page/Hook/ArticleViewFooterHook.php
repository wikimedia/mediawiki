<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleViewFooterHook {
	/**
	 * After showing the footer section of an ordinary page view
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article Article object
	 * @param ?mixed $patrolFooterShown boolean whether patrol footer is shown
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleViewFooter( $article, $patrolFooterShown );
}
