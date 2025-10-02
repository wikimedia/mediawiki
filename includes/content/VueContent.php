<?php
/**
 * Content for Vue pages.
 *
 * @license GPL-2.0-or-later
 * @since 1.44
 *
 * @file
 * @ingroup Content
 */

namespace MediaWiki\Content;

/**
 * Content for Vue pages.
 *
 * @newable
 * @ingroup Content
 */
class VueContent extends TextContent {

	/**
	 * @stable to call
	 * @param string $text Vue code.
	 * @param string $modelId the content model name
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_VUE ) {
		parent::__construct( $text, $modelId );
	}

}
