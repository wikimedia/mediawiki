<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * OutputTransformStages that only modify the content. It is expected that all inheriting classes call this class'
 * transform() method (either directly by inheritance or by calling them in the overloaded method).
 * @internal
 */
abstract class ContentTextTransformStage extends OutputTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		private readonly bool $transformBodyOnly,
	) {
		parent::__construct( $options, $logger );
	}

	/**
	 * Override this method if you need more control over which fragments
	 * should be transformed.
	 */
	protected function getFragmentsToTransform( ParserOutput $po, ParserOptions $popts ): array {
		return $this->transformBodyOnly ?
			[ ContentHolder::BODY_FRAGMENT ] :
			$po->getContentHolder()->getFragmentNames();
	}

	public function transform( ParserOutput $po, ParserOptions $popts, array &$options ): ParserOutput {
		foreach ( $this->getFragmentsToTransform( $po, $popts ) as $key ) {
			$text = $po->getContentHolder()->getAsHtmlString( $key );
			if ( $text !== null ) {
				$text = $this->transformText( $text, $po, $popts, $options );
				$po->getContentHolder()->setAsHtmlString( $key, $text );
			}
		}
		return $po;
	}

	abstract protected function transformText(
		string $text, ParserOutput $po, ParserOptions $popts, array &$options
	): string;
}
