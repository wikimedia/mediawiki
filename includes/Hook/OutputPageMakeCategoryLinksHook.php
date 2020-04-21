<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageMakeCategoryLinksHook {
	/**
	 * Links are about to be generated for the page's
	 * categories. Implementations should return false if they generate the category
	 * links, so the default link generation is skipped.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage instance (object)
	 * @param ?mixed $categories associative array, keys are category names, values are category
	 *   types ("normal" or "hidden")
	 * @param ?mixed &$links array, intended to hold the result. Must be an associative array with
	 *   category types as keys and arrays of HTML links as values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageMakeCategoryLinks( $out, $categories, &$links );
}
