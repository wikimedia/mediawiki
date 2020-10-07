<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
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
