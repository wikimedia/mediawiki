<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface CategoryViewer__generateLinkHook {
	/**
	 * This hook is called before generating an output link allow
	 * extensions opportunity to generate a more specific or relevant link.
	 *
	 * @since 1.35
	 *
	 * @param string $type Category type, either 'page', 'img', or 'subcat'
	 * @param Title $title Categorized page
	 * @param string $html Requested HTML content of anchor
	 * @param string &$link Returned value. When set to a non-null value by a hook subscriber,
	 *   this value will be used as the anchor instead of Linker::link.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryViewer__generateLink( $type, $title, $html, &$link );
}
