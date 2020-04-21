<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleFromTitleHook {
	/**
	 * when creating an article object from a title object using
	 * Wiki::articleFromTitle().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title (object) used to create the article object
	 * @param ?mixed &$article Article (object) that will be returned
	 * @param ?mixed $context IContextSource (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleFromTitle( $title, &$article, $context );
}
