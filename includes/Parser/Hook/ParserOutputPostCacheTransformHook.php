<?php

namespace MediaWiki\Parser\Hook;

use MediaWiki\Parser\ParserOutput;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserOutputPostCacheTransform" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserOutputPostCacheTransformHook {
	/**
	 * This hook is called from during the post-processing output transform pipeline to do
	 * post-cache transforms.
	 *
	 * @since 1.35
	 *
	 * @param ParserOutput $parserOutput
	 * @param string &$text Text being transformed, before core transformations are done
	 * @param array &$options Options array being used for the transformation.
	 * It can contain values documented for the $options array in
	 * \MediaWiki\Parser\ParserOutput::runOutputPipeline.
	 * Additionally, it temporarily contains the ParserOptions used to parse the ParserOutput,
	 * associated to the 'parserOptions' key. This is considered an internal behaviour and should
	 * not be relied on.
	 * A future hook will replace this one to be able to pass the desired set of parameters,
	 * including ParserOptions.
	 * @unstable since 1.46 for the 'parserOptions' key of $options
	 * @return void This hook must not abort, it must return no value
	 */
	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	): void;
}

/** @deprecated class alias since 1.46 */
class_alias( ParserOutputPostCacheTransformHook::class, 'MediaWiki\\Hook\\ParserOutputPostCacheTransformHook' );
