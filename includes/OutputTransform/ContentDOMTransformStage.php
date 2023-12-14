<?php

namespace Mediawiki\OutputTransform;

use MediaWiki\Parser\ParserOutput;
use ParserOptions;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Utils\ContentUtils;

abstract class ContentDOMTransformStage implements OutputTransformStage {

	/**
	 * @inheritDoc
	 */
	public function transform( ParserOutput $po, ?ParserOptions $popts, array &$options ): ParserOutput {
		// TODO will use HTMLHolder in the future
		$doc = ContentUtils::createAndLoadDocument( $po->getContentHolderText() );

		$doc = $this->transformDOM( $doc, $po, $popts, $options );

		// TODO will use HTMLHolder in the future
		$po->setContentHolderText( ContentUtils::ppToXML( $doc ) );
		return $po;
	}

	/** Applies the transformation to a DOM document */
	abstract public function transformDOM(
		Document $dom, ParserOutput $po, ?ParserOptions $popts, array &$options
	): Document;

}
