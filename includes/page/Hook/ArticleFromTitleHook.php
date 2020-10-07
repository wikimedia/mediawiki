<?php

namespace MediaWiki\Page\Hook;

use Article;
use IContextSource;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleFromTitleHook {
	/**
	 * This hook is called when creating an article object from a title object using
	 * Wiki::articleFromTitle().
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title used to create the article object
	 * @param Article &$article Article that will be returned
	 * @param IContextSource $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleFromTitle( $title, &$article, $context );
}
