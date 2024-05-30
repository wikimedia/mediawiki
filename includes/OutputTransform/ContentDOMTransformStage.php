<?php

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use ParserOptions;
use stdClass;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Mocks\MockEnv;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMDataUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\PHPUtils;
use Wikimedia\Parsoid\Utils\Utils;

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
	public function transform( ParserOutput $po, ?ParserOptions $popts, array &$options ): ParserOutput {
		// TODO will use HTMLHolder in the future
		$doc = null;
		$hasPageBundle = PageBundleParserOutputConverter::hasPageBundle( $po );
		$origPb = null;
		if ( $hasPageBundle ) {
			$origPb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $po );
			$doc = DOMUtils::parseHTML( $po->getContentHolderText() );
			PageBundle::apply( $doc, $origPb );
			DOMDataUtils::prepareDoc( $doc );
			$body = DOMCompat::getBody( $doc );
			'@phan-var Element $body'; // assert non-null
			DOMDataUtils::visitAndLoadDataAttribs( $body );
		} else {
			$doc = ContentUtils::createAndLoadDocument(
				$po->getContentHolderText(),
			);
		}

		$doc = $this->transformDOM( $doc, $po, $popts, $options );

		// TODO will use HTMLHolder in the future
		$body = DOMCompat::getBody( $doc );
		'@phan-var Element $body'; // assert non-null
		if ( $hasPageBundle ) {
			DOMDataUtils::visitAndStoreDataAttribs(
				$body,
				[
					'storeInPageBundle' => true,
					'env' => new MockEnv( [] ),
				]
			);
			$pb = DOMDataUtils::getPageBundle( $doc );
			$pb = self::workaroundT365036( $pb );
			$pb = self::combinePageBundles( $pb, $origPb );

			PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( $pb, $po );
			$text = ContentUtils::toXML( $body, [
				'innerXML' => true,
			] );
		} else {
			$text = ContentUtils::ppToXML( $body, [
				'innerXML' => true,
			] );
		}
		$po->setContentHolderText( $text );
		return $po;
	}

	private function combinePageBundles( stdClass $pb, ?PageBundle $origPb ): stdClass {
		if ( $origPb === null ) {
			return $pb;
		}
		$headers = $origPb->headers;
		$pb->headers = $headers ? Utils::clone( $headers ) : null;
		$pb->version = $origPb->version;
		$pb->contentmodel = $origPb->contentmodel;
		return $pb;
	}

	/**
	 * This function is necessary (T365036) because PageBundle can have
	 * non-serializable DataParsoid  inside which need
	 * to be converted to stdClass and string, respectively. We should replace this
	 * ugly workaround with something nicer.
	 */
	private function workaroundT365036( PageBundle $pb ): stdClass {
		$arrayPageBundle = PHPUtils::jsonDecode( PHPUtils::jsonEncode( $pb ) );
		return (object)$arrayPageBundle;
	}

	/** Applies the transformation to a DOM document */
	abstract public function transformDOM(
		Document $dom, ParserOutput $po, ?ParserOptions $popts, array &$options
	): Document;

}
