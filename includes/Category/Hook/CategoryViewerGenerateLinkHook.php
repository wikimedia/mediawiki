<?php

namespace MediaWiki\Category\Hook;

use MediaWiki\Context\IContextSource;
use MediaWiki\Page\PageReference;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "CategoryViewerGenerateLink" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface CategoryViewerGenerateLinkHook {
	/**
	 * This hook is called before generating an output link allow
	 * extensions opportunity to generate a more specific or relevant link.
	 *
	 * @since 1.47
	 *
	 * @param IContextSource $context Context
	 * @param string $type Category type, either 'page', 'file', or 'subcat'
	 * @param PageReference $page Categorized page
	 * @param string $html Requested HTML content of anchor
	 * @param string|null &$link Returned value. When set to a non-null value by a hook subscriber,
	 *   this value will be used as the anchor instead of LinkRenderer::makeLink.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryViewerGenerateLink(
		IContextSource $context,
		string $type,
		PageReference $page,
		string $html,
		?string &$link,
	);
}
