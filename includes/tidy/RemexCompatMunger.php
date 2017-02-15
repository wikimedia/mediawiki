<?php

namespace MediaWiki\Tidy;

use RemexHtml\HTMLData;
use RemexHtml\Serializer\Serializer;
use RemexHtml\Serializer\SerializerNode;
use RemexHtml\Tokenizer\Attributes;
use RemexHtml\Tokenizer\PlainAttributes;
use RemexHtml\TreeBuilder\TreeBuilder;
use RemexHtml\TreeBuilder\TreeHandler;
use RemexHtml\TreeBuilder\Element;

class RemexCompatMunger implements TreeHandler {
	private static $onlyInlineElements = [
		"a" => true,
		"abbr" => true,
		"acronym" => true,
		"applet" => true,
		"b" => true,
		"basefont" => true,
		"bdo" => true,
		"big" => true,
		"br" => true,
		"button" => true,
		"cite" => true,
		"code" => true,
		"dfn" => true,
		"em" => true,
		"font" => true,
		"i" => true,
		"iframe" => true,
		"img" => true,
		"input" => true,
		"kbd" => true,
		"label" => true,
		"legend" => true,
		"map" => true,
		"object" => true,
		"param" => true,
		"q" => true,
		"rb" => true,
		"rbc" => true,
		"rp" => true,
		"rt" => true,
		"rtc" => true,
		"ruby" => true,
		"s" => true,
		"samp" => true,
		"select" => true,
		"small" => true,
		"span" => true,
		"strike" => true,
		"strong" => true,
		"sub" => true,
		"sup" => true,
		"textarea" => true,
		"tt" => true,
		"u" => true,
		"var" => true,
	];

	private static $formattingElements = [
		'a' => true,
		'b' => true,
		'big' => true,
		'code' => true,
		'em' => true,
		'font' => true,
		'i' => true,
		'nobr' => true,
		's' => true,
		'small' => true,
		'strike' => true,
		'strong' => true,
		'tt' => true,
		'u' => true,
	];

	/**
	 * Constructor
	 *
	 * @param Serializer $serializer
	 */
	public function __construct( Serializer $serializer ) {
		$this->serializer = $serializer;
	}

	public function startDocument( $fragmentNamespace, $fragmentName ) {
		$this->serializer->startDocument( $fragmentNamespace, $fragmentName );
		$root = $this->serializer->getRootNode();
		$root->snData = new RemexMungerData;
		$root->snData->needsPWrapping = true;
	}

	public function endDocument( $pos ) {
		$this->serializer->endDocument( $pos );
	}

	private function getParentForInsert( $preposition, $refElement, $isBlank ) {
		if ( $preposition === TreeBuilder::ROOT ) {
			return [ $this->serializer->getRootNode(), null ];
		} elseif ( $preposition === TreeBuilder::BEFORE ) {
			return [ $this->serializer->getParentNode( $refElement ), $refElement->userData ];
		} else {
			$refNode = $refElement->userData;
			$refData = $refNode->snData;
			if ( $refData->currentCloneElement ) {
				$refElement = $refData->currentCloneElement;
				return [ $refElement->userData, $refElement->userData ];
			} elseif ( $refData->childPElement
				&& ( !$isBlank || !$refData->childPElement->userData->snData->isBlank )
			) {
				$refElement = $refData->childPElement;
				return [ $refElement->userData, $refElement->userData ];
			} else {
				return [ $refNode, $refNode ];
			}
		}
	}

	/**
	 * Insert a p-wrapper
	 *
	 * @param SerializerNode $parent
	 * @param integer $sourceStart
	 * @return SerializerNode
	 */
	private function insertPWrapper( SerializerNode $parent, $sourceStart ) {
		$pWrap = new Element( HTMLData::NS_HTML, 'mw:p-wrap', new PlainAttributes );
		$this->serializer->insertElement( TreeBuilder::UNDER, $parent, $pWrap, false,
			$sourceStart, 0 );
		$data = new RemexMungerData;
		$data->isPWrapper = true;
		$data->wrapBaseNode = $parent;
		$pWrap->userData->snData = $data;
		$parent->snData->childPElement = $pWrap;
		return $pWrap->userData;
	}

	public function characters( $preposition, $refElement, $text, $start, $length,
		$sourceStart, $sourceLength
	) {
		$isBlank = strspn( $text, "\t\n\f\r ", $start, $length ) !== $length;

		list( $parent, $refNode ) = $this->getParentForInsert(
			$preposition, $refElement, $isBlank );
		$parentData = $parent->snData;

		// If the parent is splittable and in block mode, split the tag stack
		if ( $preposition === TreeBuilder::UNDER
			&& $parentData->isSplittable
			&& !$parentData->ancestorPNode
		) {
			$refNode = $this->splitTagStack( $refNode, true, $sourceStart );
			$parent = $refNode;
			$parentData = $parent->snData;
		}

		if ( $preposition === TreeBuilder::UNDER && $parentData->needsPWrapping ) {
			$refNode = $this->insertPWrapper( $refNode, $sourceStart );
			$parent = $refNode;
			$parentData = $parent->snData;
		}

		if ( $isBlank ) {
			// Non-whitespace characters detected
			$parentData->hasText = true;
			$parentData->isBlank = false;
		}
		$this->serializer->characters( $preposition, $refNode, $text, $start,
			$length, $sourceStart, $sourceLength );
	}

	public function insertElement( $preposition, $refElement, Element $element, $void,
		$sourceStart, $sourceLength
	) {
		list( $parent, $newRef ) = $this->getParentForInsert(
			$preposition, $refElement, false );
		$parentData = $parent->snData;
		$parentNs = $parent->namespace;
		$parentName = $parent->name;
		$elementName = $element->htmlName;

		$inline = isset( self::$onlyInlineElements[$elementName] );
		$under = $preposition === TreeBuilder::UNDER;

		// If the element is non-inline and the parent is a p-wrapper,
		// close the parent and insert into its parent instead
		if ( $under && $parentData->isPWrapper ) {
			if ( !$inline ) {
				$newParent = $this->serializer->getParentNode( $parent );
				$parent = $newParent;
				$parentData = $parent->snData;
				$parentData->childPElement = null;
				$newRef = $refElement->userData;
				// FIXME cannot call endTag() since we don't have an Element
			}
		}

		// If the parent is splittable and the current element is inline in block
		// context, or if the current element is a block under a p-wrapper, split
		// the tag stack.
		if ( $under && $parentData->isSplittable
			&& (bool)$parentData->ancestorPNode !== $inline
		) {
			$newRef = $this->splitTagStack( $newRef, $inline, $sourceStart );
			$parent = $newRef;
			$parentData = $parent->snData;
		}

		// If the element is inline and we are in body/blockquote, we need
		// to create a p-wrapper
		if ( $under && $parentData->needsPWrapping && $inline ) {
			$newRef = $this->insertPWrapper( $newRef, $sourceStart );
			$parent = $newRef;
			$parentData = $parent->snData;
		}

		// If the element is non-inline and (despite attempting to split above)
		// there is still an ancestor p-wrap, disable that p-wrap
		if ( $parentData->ancestorPNode && !$inline ) {
			$parentData->ancestorPNode->snData->isDisabledPWrapper = true;
		}

		// An element with element children is a non-blank element
		$parentData->isBlank = false;

		// Insert the element downstream and so initialise its userData
		$this->serializer->insertElement( $preposition, $newRef,
			$element, $void, $sourceStart, $sourceLength );

		// Initialise snData
		if ( !$element->userData->snData ) {
			$elementData = $element->userData->snData = new RemexMungerData;
		}
		if ( ( $parentData->isPWrapper || $parentData->isSplittable )
			&& isset( self::$formattingElements[$elementName] )
		) {
			$elementData->isSplittable = true;
		}
		if ( $parentData->isPWrapper ) {
			$elementData->ancestorPNode = $parent;
		} elseif ( $parentData->ancestorPNode ) {
			$elementData->ancestorPNode = $parentData->ancestorPNode;
		}
		if ( $parentData->wrapBaseNode ) {
			$elementData->wrapBaseNode = $parentData->wrapBaseNode;
		} elseif ( $parentData->needsPWrapping ) {
			$elementData->wrapBaseNode = $parent;
		}
		if ( $elementName === 'body'
			|| $elementName === 'blockquote'
			|| $elementName === 'html'
		) {
			$elementData->needsPWrapping = true;
		}
	}

	/**
	 * Clone nodes in a stack range and return the new parent
	 *
	 * @param SerializerNode $parentNode
	 * @param bool $inline
	 * @param integer $pos The source position
	 * @return SerializerNode
	 */
	private function splitTagStack( SerializerNode $parentNode, $inline, $pos ) {
		$parentData = $parentNode->snData;
		$wrapBase = $parentData->wrapBaseNode;
		if ( $inline ) {
			$cloneEnd = $wrapBase;
		} else {
			$cloneEnd = $parentData->ancestorPNode;
		}

		$serializer = $this->serializer;
		$node = $parentNode;
		$haveContent = false;
		$root = $serializer->getRootNode();
		while ( $node !== $cloneEnd ) {
			$haveContent = $haveContent || $node->snData->hasText;

			$nodes[] = $node;
			$node = $serializer->getParentNode( $node );
			if ( $node === $root ) {
				throw new \Exception( 'Did not find end of clone range' );
			}
		}

		if ( !$haveContent ) {
			return $parentNode;
		}

		if ( $inline ) {
			$pWrap = $this->insertPWrapper( $wrapBase, $pos );
			$node = $pWrap;
			$nodes[] = $node;
		} else {
			$pWrap = null;
			$node = $wrapBase;
			$nodes[] = $node;
		}

		for ( $i = count( $nodes ) - 2; $i >= 0; $i-- ) {
			$node = $nodes[$i];
			$nodeParent = $nodes[$i + 1];
			$element = new Element( $node->namespace, $node->name, $node->attrs );
			$this->serializer->insertElement( TreeBuilder::UNDER, $nodeParent,
				$element, false, $pos, 0 );
			$node->snData->currentCloneElement = $element;

			$node = $element->userData;
			$elementData = $node->snData = new RemexMungerData;
			if ( $pWrap ) {
				$elementData->ancestorPNode = $pWrap;
			}
			$elementData->isSplittable = true;
			$elementData->wrapBaseNode = $wrapBase;
		}
		return $node;
	}

	public function endTag( Element $element, $sourceStart, $sourceLength ) {
		$this->serializer->endTag( $element, $sourceStart, $sourceLength );
	}

	public function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
		$this->serializer->doctype( $name, $public,  $system, $quirks,
			$sourceStart, $sourceLength );
	}

	public function comment( $preposition, $refElement, $text, $sourceStart, $sourceLength ) {
		list( $parent, $refNode ) = $this->getParentForInsert(
			$preposition, $refElement, true );
		$this->serializer->comment( $preposition, $refNode, $text,
			$sourceStart, $sourceLength );
	}

	public function error( $text, $pos ) {
		$this->serializer->error( $text, $pos );
	}

	public function mergeAttributes( Element $element, Attributes $attrs, $sourceStart ) {
		$this->serializer->mergeAttributes( $element, $attrs, $sourceStart );
	}

	public function removeNode( Element $element, $sourceStart ) {
		$this->serializer->removeNode( $element, $sourceStart );
	}

	public function reparentChildren( Element $element, Element $newParent, $sourceStart ) {
		$this->insertElement( TreeBuilder::UNDER, $element, $newParent, false, $sourceStart, 0 );
		$this->serializer->reparentChildren( $element, $newParent, $sourceStart );
	}
}
