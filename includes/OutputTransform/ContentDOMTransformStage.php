<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Utils\DOMCompat;

/**
 * OutputTransformStages that modify the content as a HTML DOM tree.
 *
 * Subclasses are expected to implement ::transformDOM() to mutate the
 * DOM-structured content as a Document in-place.
 *
 * @internal
 */
abstract class ContentDOMTransformStage extends OutputTransformStage
	implements DOMTransformStage {

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

	/**
	 * @inheritDoc
	 */
	public function transform(
		ParserOutput $po, ParserOptions $popts, array &$options
	): ParserOutput {
		foreach ( $this->getFragmentsToTransform( $po, $popts ) as $key ) {
			$dom = $po->getContentHolder()->getAsDom( $key );
			if ( $dom ) {
				$dom = $this->transformDOM( $dom, $po, $popts, $options );
				$po->getContentHolder()->setAsDom( $key, $dom );
			}
		}
		return $po;
	}

	/** Applies the transformation to a DOM document */
	abstract public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ParserOptions $popts, array &$options
	): DocumentFragment;

	/**
	 * Helper method for DOM transforms to easily create DOM Elements with
	 * the given attributes and children.
	 *
	 * @param Document $doc Document holding the new element
	 * @param string $name Lowercase tag name of the new element
	 * @param array<string,string> $attribs Associative array between the
	 *   name and (unescaped) value of the attributes of the new element
	 * @param Node|string ...$children List of child nodes for the new element.
	 *   Unescaped strings are converted to new Text Nodes before their
	 *   insertion in the tree.
	 * @return Element
	 * @throws \DOMException
	 */
	public function createElement(
		Document $doc, string $name, array $attribs = [], Node|string ...$children
	): Element {
		$el = $doc->createElement( $name );
		foreach ( $attribs as $key => $value ) {
			$el->setAttribute( $key, $value );
		}
		DOMCompat::append( $el, ...$children );
		return $el;
	}
}
