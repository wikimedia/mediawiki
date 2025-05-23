<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;

/**
 * OutputTransformStages that can modify the content either as a DOM tree or an HTML string.
 *
 * It contains both a ContentDOMTransformStage for the former, and a ContentTextTransformStage for the latter,
 * and can be set up to either consider these interchangeable depending on what is useful, or to be exclusive if
 * they do not provide the same function (e.g. passes that do not behave in the same way for Parsoid and
 * Legacy could be defined as exclusive Parsoid-DOM and Legacy-Html).
 *
 * @internal
 */
class ContentHolderTransformStage extends OutputTransformStage {
	public function __construct(
		ServiceOptions $options, LoggerInterface $logger,
		/** The HTML transform. */
		private ContentTextTransformStage $textTransform,
		/** The DOM tranform. */
		private ContentDOMTransformStage $domTransform,
		/**
		 * True if only one of $textTransform and $domTransform are expected to apply;
		 * false if both transforms are equivalent and either can be selected depending
		 * on the ContentHolder preferences.
		 */
		private bool $exclusive = false
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		$textShouldRun = $this->textTransform->shouldRun( $po, $popts, $options );
		$domShouldRun = $this->domTransform->shouldRun( $po, $popts, $options );
		$textClass = $this->textTransform::class;
		$domClass = $this->domTransform::class;
		Assert::invariant( $this->exclusive ?
				!( $textShouldRun && $domShouldRun ) : $textShouldRun === $domShouldRun,
			"Bad pipeline stage definition for $textClass / $domClass"
		);
		return $textShouldRun || $domShouldRun;
	}

	public function transform( ParserOutput $po, ?ParserOptions $popts, array &$options ): ParserOutput {
		$useDom = $this->exclusive ?
			$this->domTransform->shouldRun( $po, $popts, $options ) :
			$po->getContentHolder()->preferDom();
		return $useDom ? $this->domTransform->transform( $po, $popts, $options ) :
			$this->textTransform->transform( $po, $popts, $options );
	}
}
