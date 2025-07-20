<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use Wikimedia\Parsoid\Core\DomPageBundle;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * OutputTransformStages that modify the content as a HTML DOM tree.
 *
 * Subclasses are expected to implement ::transformDOM() to mutate the
 * DOM-structured content as a Document in-place.
 *
 * @internal
 */
abstract class ContentDOMTransformStage extends OutputTransformStage {

	/**
	 * @inheritDoc
	 */
	public function transform(
		ParserOutput $po, ?ParserOptions $popts, array &$options
	): ParserOutput {
		if ( $options['isParsoidContent'] ?? false ) {
			return $this->parsoidTransform( $po, $popts, $options );
		} else {
			return $this->legacyTransform( $po, $popts, $options );
		}
	}

	private function legacyTransform(
		ParserOutput $po, ?ParserOptions $popts, array &$options
	): ParserOutput {
		$text = $po->getContentHolderText();
		$doc = DOMUtils::parseHTML( $text );

		$doc = $this->transformDOM( $doc, $po, $popts, $options );

		$body = DOMCompat::getBody( $doc );
		$text = ContentUtils::toXML( $body, [
			'innerXML' => true,
		] );
		$po->setContentHolderText( $text );
		return $po;
	}

	private function parsoidTransform(
		ParserOutput $po, ?ParserOptions $popts, array &$options
	): ParserOutput {
		// TODO will use HTMLHolder in the future
		$doc = null;
		$hasPageBundle = PageBundleParserOutputConverter::hasPageBundle( $po );
		$origPb = null;
		if ( $hasPageBundle ) {
			$origPb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $po );
			// TODO: pageBundleFromParserOutput should be able to create a
			// DomPageBundle when the HTMLHolder has a DOM already.
			$doc = DomPageBundle::fromPageBundle( $origPb )->toDom( true );
		} else {
			$doc = ContentUtils::createAndLoadDocument(
				$po->getContentHolderText(), [ 'markNew' => true, 'validateXMLNames' => true, ]
			);
		}

		$doc = $this->transformDOM( $doc, $po, $popts, $options );

		// TODO will use HTMLHolder/DomPageBundle in the future
		if ( $hasPageBundle ) {
			$services = MediaWikiServices::getInstance();
			$dpb = DomPageBundle::fromLoadedDocument( $doc, [
				'pageBundle' => $origPb,
				'siteConfig' => $services->getParsoidSiteConfig(),
			] );
			$pb = PageBundle::fromDomPageBundle( $dpb, [ 'body_only' => true ] );
			PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( $pb, $po );
			$text = $pb->html;
		} else {
			$body = DOMCompat::getBody( $doc );
			'@phan-var Element $body'; // assert non-null
			$text = ContentUtils::ppToXML( $body, [
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
