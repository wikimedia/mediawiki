<?php

namespace MediaWiki\Output\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageMakeCategoryLinks" to register handlers implementing this interface.
 *
 * @deprecated since 1.43, use OutputPageRenderCategoryLinkHook instead.
 * @ingroup Hooks
 */
interface OutputPageMakeCategoryLinksHook {
	/**
	 * This hook is called when links are about to be generated for the page's categories.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @param string[] $categories Associative array in which keys are category names and
	 *   values are category types ("normal" or "hidden")
	 * @param array &$links Intended to hold the result. Associative array with
	 *   category types as keys and arrays of HTML links as values.
	 * @return bool|void True or no return value to continue. Implementations should return
	 *   false if they generate the category links, so the default link generation is skipped.
	 */
	public function onOutputPageMakeCategoryLinks( $out, $categories, &$links );
}

/** @deprecated class alias since 1.42 */
class_alias( OutputPageMakeCategoryLinksHook::class, 'MediaWiki\Hook\OutputPageMakeCategoryLinksHook' );
