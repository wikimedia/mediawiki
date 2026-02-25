<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * This trait can be used to implement OutputTransformFirstStageHook or
 * OutputTransformLastStageHook in any subclass of OutputTransformStage,
 * which will allow that stage class to be used directly as a hook handler.
 *
 * This is optional, but can be useful for transformations that wish to
 * extend ContentTextTransformStage, ContentDomTransformStage,
 * ContentHolderTransformStage, or some existing transform.
 *
 * For example, a simple text transformation can be defined in an extension
 * like:
 * ```php
 * class SampleTransform
 *     extends ContentTextTransformStage
 *     implements OutputTransformFirstStageHook {
 *   use OutputTransformStageHookTrait;
 *
 *   public function __construct(
 *     Config $mainConfig,
 *     private readonly MyService $myService,
 *   ) {
 *     parent::__construct(
 *       new ServiceOptions( static::CONSTRUCTOR_OPTIONS, $mainConfig ),
 *       LoggerFactory::getInstance( 'SampleTransform' )
 *     );
 *   }
 *
 *   public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
 *      return $po->getTitle()?->inNamespace( NS_TALK );
 *   }
 *
 *   protected function transformText( string $text, ...) {
 *     return $text . "<!-- sample talk page transform -->";
 *   }
 * }
 * ```
 *
 * And then this would be used as a hook handler by including the following
 * in `extension.json`:
 * ```json
 * {
 *   "HookHandlers": {
 *     "transform": {
 *       "class": "MyExtension\\SampleTransform",
 *       "services": [
 *         "MainConfig",
 *         "MyService",
 *       ]
 *     }
 *   },
 *   "Hooks": {
 *     "OutputTransformFirstStage": "transform"
 *   }
 * }
 * ```
 *
 * This trait allows extensions to implement OutputTransformStages in the same
 * way that they are implemented in core. This can be helpful when moving
 * code from extensions to core or vice-versa and provides a natural
 * way to provide service objects to your transform.  For very simple
 * transforms, however, directly implementing the hook (not using this
 * trait) may be preferable.
 *
 * You can see more examples of the use of the trait in the test cases
 * `ExecuteFirstStageTransformHooksTest` and
 * `ExecuteLastStageTransformHooksTest`.
 */
trait OutputTransformStageHookTrait {

	/** @inheritDoc */
	public function onOutputTransformFirstStage(
		ParserOutput &$parserOutput, ParserOptions $parserOptions
	): void {
		'@phan-var OutputTransformStage $this';
		if ( $this->shouldRun( $parserOutput, $parserOptions ) ) {
			$ignore = [];
			$parserOutput = $this->transform(
				$parserOutput, $parserOptions, $ignore
			);
		}
	}

	/** @inheritDoc */
	public function onOutputTransformLastStage(
		ParserOutput &$parserOutput, ParserOptions $parserOptions
	): void {
		'@phan-var OutputTransformStage $this';
		if ( $this->shouldRun( $parserOutput, $parserOptions ) ) {
			$ignore = [];
			$parserOutput = $this->transform(
				$parserOutput, $parserOptions, $ignore
			);
		}
	}
}
