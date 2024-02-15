<?php

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use ParserOptions;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Mocks\MockEnv;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMDataUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * OutputTransformStages that modify the content as a HTML DOM tree.
 *
 * Subclasses are expected to implement ::transformDOM() to mutate the
 * DOM-structured content as a Document in-place.
 *
 * @internal
 */
abstract class ContentDOMTransformStage implements OutputTransformStage {

	/**
	 * @inheritDoc
	 */
	public function transform( ParserOutput $po, ?ParserOptions $popts, array &$options ): ParserOutput {
		// TODO will use HTMLHolder in the future
		$doc = null;
		$hasPageBundle = PageBundleParserOutputConverter::hasPageBundle( $po );
		if ( $hasPageBundle ) {
			$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $po );
			$doc = DOMUtils::parseHTML( $po->getContentHolderText() );
			PageBundle::apply( $doc, $pb );
			DOMDataUtils::prepareDoc( $doc );
			DOMDataUtils::visitAndLoadDataAttribs(
				DOMCompat::getBody( $doc )
			);
		} else {
			$doc = ContentUtils::createAndLoadDocument(
				$po->getContentHolderText(),
			);
		}

		$doc = $this->transformDOM( $doc, $po, $popts, $options );

		// TODO will use HTMLHolder in the future
		if ( $hasPageBundle ) {
			DOMDataUtils::visitAndStoreDataAttribs(
				DOMCompat::getBody( $doc ),
				[
					'storeInPageBundle' => true,
					'env' => new MockEnv( [] ),
				]
			);
			$pb = DOMDataUtils::getPageBundle( $doc );
			$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
				$pb, $po
			);
			$text = ContentUtils::toXML( DOMCompat::getBody( $doc ), [
				'innerXML' => true,
			] );
		} else {
			$text = ContentUtils::ppToXML( DOMCompat::getBody( $doc ), [
				'innerXML' => true,
			] );
		}
		$po->setContentHolderText( $text );
		return $po;
	}

	/** Applies the transformation to a DOM document */
	abstract public function transformDOM(
		Document $dom, ParserOutput $po, ?ParserOptions $popts, array &$options
	): Document;

}
