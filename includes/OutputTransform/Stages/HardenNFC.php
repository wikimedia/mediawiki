<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use Psr\Log\LoggerInterface;

/**
 * Hardens the output against NFC normalization (T387130).
 * @internal
 */
class HardenNFC extends ContentTextTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
	) {
		parent::__construct( $options, $logger, transformBodyOnly: false );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return true;
	}

	protected function transformText( string $text, ParserOutput $po, ParserOptions $popts, array &$options ): string {
		return Sanitizer::escapeCombiningChar( $text );
	}
}
