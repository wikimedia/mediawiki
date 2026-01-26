<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * Runs the onParserOutputPostCacheTransform hook.
 */
class ExecutePostCacheTransformHooks extends ContentTextTransformStage {

	private HookContainer $hookContainer;
	private HookRunner $hookRunner;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, HookContainer $hookContainer
	) {
		parent::__construct( $options, $logger );
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->hookContainer = $hookContainer;
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $this->hookContainer->isRegistered( 'ParserOutputPostCacheTransform' );
	}

	/**
	 * @note 1.46 It is currently expected that $options does not contain a 'parserOptions' key or that the
	 * associated value is of type ParserOptions (it will be overwritten and later unset otherwise).
	 */
	protected function transformText( string $text, ParserOutput $po, ParserOptions $popts, array &$options
	): string {
		// TODO This is only to be used by DiscussionTools, as a stopgap before we alter the hook signature of
		// onParserOutputPostCacheTransform
		$setPopts = !isset( $options['parserOptions'] ) || !( $options[ 'parserOptions' ] instanceof ParserOptions );
		if ( $setPopts ) {
			$options['parserOptions'] = $popts;
		}

		$this->hookRunner->onParserOutputPostCacheTransform( $po, $text, $options );
		if ( $setPopts ) {
			unset( $options['parserOptions'] );
		}
		return $text;
	}
}
