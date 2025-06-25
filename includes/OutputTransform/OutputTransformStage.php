<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Classes implementing the OutputTransformStage aim at being added to a pipeline of transformations that transform
 * a ParserOutput. The argument ParserOutput can explicitly be modified in place; ensuring that cached objects
 * do not suffer from side effects is the caller's (typically the pipeline's) responsibility.
 * @unstable
 */
abstract class OutputTransformStage {
	protected ServiceOptions $options;
	protected LoggerInterface $logger;

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [];

	/** @internal */
	public function __construct( ServiceOptions $options, LoggerInterface $logger ) {
		// Note this is static:: not self:: so we use the subclass options
		$options->assertRequiredOptions( static::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->logger = $logger;
	}

	/**
	 * Decides whether or not the stage should be run
	 * @param ParserOutput $po
	 * @unstable
	 * @param ParserOptions|null $popts
	 * @param array $options
	 * @return bool
	 */
	abstract public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool;

	/**
	 * Transforms the input ParserOutput into the returned ParserOutput.
	 * The returned ParserOutput can explicitly be a modified version of the input ParserOutput; if modifications
	 * to that object are unexpected, a copy should be made before passing it to this method.
	 * TODO Some transformations require the possibility of modifying options (this is the case of
	 * ExecutePostCacheTransformHooks in particular). We do NOT want to keep this mechanism for later versions of
	 * this interface - the currently foreseen goal is to not pass $options at all.
	 * Modifying $options during this pass is considered deprecated.
	 * @unstable
	 */
	abstract public function transform( ParserOutput $po, ?ParserOptions $popts, array &$options ): ParserOutput;
}
