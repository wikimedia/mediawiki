<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Adds RedirectHeader if it exists
 * @internal
 */
class AddRedirectHeader extends ContentTextTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
	) {
		parent::__construct( $options, $logger, transformBodyOnly: true );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $po->getRedirectHeader() !== null;
	}

	protected function transformText( string $text, ParserOutput $po, ParserOptions $popts, array &$options ): string {
		return $po->getRedirectHeader() . $text;
	}
}
