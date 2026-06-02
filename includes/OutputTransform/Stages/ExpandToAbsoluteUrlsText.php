<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\Linker;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Expand relative links to absolute URLs
 * @internal
 */
class ExpandToAbsoluteUrlsText extends ContentTextTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
	) {
		parent::__construct( $options, $logger, transformBodyOnly: false );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $options['absoluteURLs'] ?? false;
	}

	protected function transformText( string $text, ParserOutput $po, ParserOptions $popts, array &$options ): string {
		return Linker::expandLocalLinks( $text );
	}

}
