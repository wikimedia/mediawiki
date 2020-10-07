<?php

namespace MediaWiki\Hook;

use ParserOutput;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserOutputPostCacheTransformHook {
	/**
	 * This hook is called from ParserOutput::getText() to do
	 * post-cache transforms.
	 *
	 * @since 1.35
	 *
	 * @param ParserOutput $parserOutput
	 * @param string &$text Text being transformed, before core transformations are done
	 * @param array &$options Options array being used for the transformation
	 * @return void This hook must not abort, it must return no value
	 */
	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	) : void;
}
