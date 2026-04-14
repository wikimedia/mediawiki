<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * OutputTransformStages that only modify the content. It is expected that all inheriting classes call this class'
 * transform() method (either directly by inheritance or by calling them in the overloaded method).
 * @internal
 */
abstract class ContentTextTransformStage extends OutputTransformStage {

	public function transform( ParserOutput $po, ParserOptions $popts, array &$options ): ParserOutput {
		foreach ( $this->getFragmentsToTransform( $po, $popts ) as $key ) {
			if ( $key === ContentHolder::BODY_FRAGMENT ) {
				$text = $po->getContentHolderText();
			} else {
				$text = $po->getContentHolder()->getAsHtmlString( $key );
			}
			if ( $text !== null ) {
				$text = $this->transformText( $text, $po, $popts, $options );
				if ( $key === ContentHolder::BODY_FRAGMENT ) {
					$po->setContentHolderText( $text );
				} else {
					$po->getContentHolder()->setAsHtmlString( $key, $text );
				}
			}
		}
		return $po;
	}

	abstract protected function transformText(
		string $text, ParserOutput $po, ParserOptions $popts, array &$options
	): string;
}
