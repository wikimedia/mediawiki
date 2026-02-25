<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Hook;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 *
 * Use the hook name "OutputTransformLastStage" to register handlers
 * implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputTransformLastStageHook {
	/**
	 * This hook is called from the post-processing output transform pipeline
	 * at the very end of processing to do post-cache transformations.
	 *
	 * You can implement this hook directly for simple transforms.
	 *
	 * However, if you would like to use the machinery of
	 * OutputTransformStage (for example, ContentHolderTransformStage
	 * for transforms that can operate on either HTML or DOM), you can
	 * use the OutputTransformStageHookTrait to implement this hook
	 * from any class that extends OutputTransformStage.
	 *
	 * @since 1.47
	 *
	 * @param ParserOutput &$parserOutput The ParserOutput to modify
	 * @param ParserOptions $parserOptions Options controlling this transformation
	 */
	public function onOutputTransformLastStage(
		ParserOutput &$parserOutput, ParserOptions $parserOptions
	): void;
}
