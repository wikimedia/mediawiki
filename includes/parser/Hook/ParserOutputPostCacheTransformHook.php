<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserOutputPostCacheTransformHook {
	/**
	 * Called from ParserOutput::getText() to do
	 * post-cache transforms.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parserOutput The ParserOutput object.
	 * @param ?mixed &$text The text being transformed, before core transformations are done.
	 * @param ?mixed &$options The options array being used for the transformation.
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	);
}
