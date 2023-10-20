<?php

namespace Mediawiki\OutputTransform\Stages;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use Mediawiki\OutputTransform\ContentTextTransformStage;
use ParserOptions;
use ParserOutput;

/**
 * @internal
 * Runs the onParserOutputPostCacheTransform hook.
 */
class PostCacheTransformHookRunner extends ContentTextTransformStage {

	private HookContainer $hookContainer;
	private HookRunner $hookRunner;

	public function __construct( HookContainer $hookContainer ) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->hookContainer = $hookContainer;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $this->hookContainer->isRegistered( 'ParserOutputPostCacheTransform' );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options
	): string {
		$this->hookRunner->onParserOutputPostCacheTransform( $po, $text, $options );
		return $text;
	}
}
