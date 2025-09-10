<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\DOM\DocumentFragment;

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
		$contentHolder = $po->getContentHolder();
		$df = $contentHolder->getAsDom( ContentHolder::BODY_FRAGMENT ) ??
			$contentHolder->createFragment();

		$df = $this->transformDOM( $df, $po, $popts, $options );

		$contentHolder->setAsDom( ContentHolder::BODY_FRAGMENT, $df );
		return $po;
	}

	/** Applies the transformation to a DOM document */
	abstract public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ?ParserOptions $popts, array &$options
	): DocumentFragment;

}
