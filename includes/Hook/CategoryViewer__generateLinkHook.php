<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryViewer__generateLinkHook {
	/**
	 * Before generating an output link allow
	 * extensions opportunity to generate a more specific or relevant link.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type The category type. Either 'page', 'img' or 'subcat'
	 * @param ?mixed $title Title object for the categorized page
	 * @param ?mixed $html Requested html content of anchor
	 * @param ?mixed &$link Returned value. When set to a non-null value by a hook subscriber
	 *   this value will be used as the anchor instead of Linker::link
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryViewer__generateLink( $type, $title, $html, &$link );
}
