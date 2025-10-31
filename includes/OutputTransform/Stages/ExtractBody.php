<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;

/**
 * Strip everything but the <body>
 * @internal
 */
class ExtractBody extends ContentTextTransformStage {
	public function __construct(
		ServiceOptions $options, LoggerInterface $logger,
		private UrlUtils $urlUtils
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $po->getContentHolder()->isParsoidContent();
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		return Parser::extractBody( $text );
	}
}
