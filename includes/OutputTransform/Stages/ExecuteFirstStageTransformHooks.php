<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * Runs the onOutputTransformFirstStage hook.
 */
class ExecuteFirstStageTransformHooks extends OutputTransformStage {

	private HookRunner $hookRunner;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger,
		private HookContainer $hookContainer
	) {
		parent::__construct( $options, $logger );
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $this->hookContainer->isRegistered( 'OutputTransformFirstStage' );
	}

	public function transform( ParserOutput $po, ParserOptions $popts, array &$options ): ParserOutput {
		$this->hookRunner->onOutputTransformFirstStage( $po, $popts );
		return $po;
	}
}
