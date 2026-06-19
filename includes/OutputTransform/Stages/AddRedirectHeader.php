<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Adds RedirectHeader if it exists
 * @internal
 */
class AddRedirectHeader extends OutputTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $po->getRedirectHeader() !== null;
	}

	public function transform( ParserOutput $po, ParserOptions $popts, array &$options ): ParserOutput {
		$redirectHeader = $po->getRedirectHeader();
		'@phan-var string $redirectHeader';
		$po->getContentHolder()->prependHtmlString( $redirectHeader );
		return $po;
	}
}
