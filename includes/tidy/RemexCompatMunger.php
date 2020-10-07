<?php

namespace MediaWiki\Tidy;

use RemexHtml\HTMLData;
use RemexHtml\Serializer\Serializer;
use RemexHtml\Serializer\SerializerNode;
use RemexHtml\Tokenizer\Attributes;
use RemexHtml\Tokenizer\PlainAttributes;
use RemexHtml\TreeBuilder\Element;
use RemexHtml\TreeBuilder\TreeBuilder;
use RemexHtml\TreeBuilder\TreeHandler;

/**
 * @internal
 */
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
		"del" => true,
		"dfn" => true,
		"em" => true,
		"font" => true,
		"i" => true,
		"iframe" => true,
		"img" => true,
		"input" => true,
		"ins" => true,
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
		// Those defined in tidy.conf
		"video" => true,
		"audio" => true,
		"bdi" => true,
		"data" => true,
		"time" => true,
		"mark" => true,
	];

	/**
	 * For the purposes of this class, "metadata" elements are those that
	 * should neither trigger p-wrapping nor stop an outer p-wrapping,
	 * typically those that are themselves invisible in a browser's rendering.
	 * This isn't a complete list, it's just the tags that we're likely to
	 * encounter in practice.
	 * @var array
	 */
	private static $metadataElements = [
		'style' => true,
		'script' => true,
		'link' => true,
		'meta' => true,
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

	/** @var Serializer */
	private $serializer;

	/** @var bool */
	private $trace;

	/**
	 * @param Serializer $serializer
	 * @param bool $trace
	 */
	public function __construct( Serializer $serializer, $trace = false ) {
		$this->serializer = $serializer;
		$this->trace = $trace;
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

	private function getParentForInsert( $preposition, $refElement ) {
		if ( $preposition === TreeBuilder::ROOT ) {
			return [ $this->serializer->getRootNode(), null ];
		} elseif ( $preposition === TreeBuilder::BEFORE ) {
			$refNode = $refElement->userData;
			return [ $this->serializer->getParentNode( $refNode ), $refNode ];
		} else {
			$refNode = $refElement->userData;
			$refData = $refNode->snData;
			if ( $refData->currentCloneElement ) {
				// Follow a chain of clone links if necessary
				$origRefData = $refData;
				while ( $refData->currentCloneElement ) {
					$refElement = $refData->currentCloneElement;
					$refNode = $refElement->userData;
					$refData = $refNode->snData;
				}
				// Cache the end of the chain in the requested element
				$origRefData->currentCloneElement = $refElement;
			} elseif ( $refData->childPElement ) {
				$refElement = $refData->childPElement;
				$refNode = $refElement->userData;
			}
			return [ $refNode, $refNode ];
		}
	}

	/**
	 * Insert a p-wrapper
	 *
	 * @param SerializerNode $parent
	 * @param int $sourceStart
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
		$isBlank = strspn( $text, "\t\n\f\r ", $start, $length ) === $length;

		list( $parent, $refNode ) = $this->getParentForInsert( $preposition, $refElement );
		$parentData = $parent->snData;

		if ( $preposition === TreeBuilder::UNDER ) {
			if ( $parentData->needsPWrapping && !$isBlank ) {
				// Add a p-wrapper for bare text under body/blockquote
				$refNode = $this->insertPWrapper( $refNode, $sourceStart );
				$parent = $refNode;
				$parentData = $parent->snData;
			} elseif ( $parentData->isSplittable && !$parentData->ancestorPNode ) {
				// The parent is splittable and in block mode, so split the tag stack
				$refNode = $this->splitTagStack( $refNode, true, $sourceStart );
				$parent = $refNode;
				$parentData = $parent->snData;
			}
		}

		if ( !$isBlank ) {
			// Non-whitespace characters detected
			$parentData->nonblankNodeCount++;
		}
		$this->serializer->characters( $preposition, $refNode, $text, $start,
			$length, $sourceStart, $sourceLength );
	}

	private function trace( $msg ) {
		if ( $this->trace ) {
			wfDebug( "[RCM] $msg" );
		}
	}

	/**
	 * Insert or reparent an element. Create p-wrappers or split the tag stack
	 * as necessary.
	 *
	 * Consider the following insertion locations. The parent may be:
	 *
	 *   - A: A body or blockquote (!!needsPWrapping)
	 *   - B: A p-wrapper (!!isPWrapper)
	 *   - C: A descendant of a p-wrapper (!!ancestorPNode)
	 *     - CS: With splittable formatting elements in the stack region up to
	 *       the p-wrapper
	 *     - CU: With one or more unsplittable elements in the stack region up
	 *       to the p-wrapper
	 *   - D: Not a descendant of a p-wrapper (!ancestorNode)
	 *     - DS: With splittable formatting elements in the stack region up to
	 *       the body or blockquote
	 *     - DU: With one or more unsplittable elements in the stack region up
	 *       to the body or blockquote
	 *
	 * And consider that we may insert two types of element:
	 *   - b: block
	 *   - i: inline
	 *
	 * We handle the insertion as follows:
	 *
	 *   - A/i: Create a p-wrapper, insert under it
	 *   - A/b: Insert as normal
	 *   - B/i: Insert as normal
	 *   - B/b: Close the p-wrapper, insert under the body/blockquote (wrap
	 *     base) instead)
	 *   - C/i: Insert as normal
	 *   - CS/b: Split the tag stack, insert the block under cloned formatting
	 *     elements which have the wrap base (the parent of the p-wrap) as
	 *     their ultimate parent.
	 *   - CU/b: Disable the p-wrap, by reparenting the currently open child
	 *     of the p-wrap under the p-wrap's parent. Then insert the block as
	 *     normal.
	 *   - D/b: Insert as normal
	 *   - DS/i: Split the tag stack, creating a new p-wrapper as the ultimate
	 *     parent of the formatting elements thus cloned. The parent of the
	 *     p-wrapper is the body or blockquote.
	 *   - DU/i: Insert as normal
	 *
	 * FIXME: fostering ($preposition == BEFORE) is mostly done by inserting as
	 * normal, the full algorithm is not followed.
	 *
	 * @param int $preposition
	 * @param Element|SerializerNode|null $refElement
	 * @param Element $element
	 * @param bool $void
	 * @param int $sourceStart
	 * @param int $sourceLength
	 */
	public function insertElement( $preposition, $refElement, Element $element, $void,
		$sourceStart, $sourceLength
	) {
		list( $parent, $newRef ) = $this->getParentForInsert( $preposition, $refElement );
		$parentData = $parent->snData;
		$elementName = $element->htmlName;

		$inline = isset( self::$onlyInlineElements[$elementName] );
		$under = $preposition === TreeBuilder::UNDER;
		$elementToEnd = null;

		if ( isset( self::$metadataElements[$elementName] ) ) {
			// The element is a metadata element, that we allow to appear in
			// both inline and block contexts.
			$this->trace( 'insert metadata' );
		} elseif ( $under && $parentData->isPWrapper && !$inline ) {
			// [B/b] The element is non-inline and the parent is a p-wrapper,
			// close the parent and insert into its parent instead
			$this->trace( 'insert B/b' );
			$newParent = $this->serializer->getParentNode( $parent );
			$parent = $newParent;
			$parentData = $parent->snData;
			$parentData->childPElement = null;
			$newRef = $refElement->userData;
		} elseif ( $under && $parentData->isSplittable
			&& (bool)$parentData->ancestorPNode !== $inline
		) {
			// [CS/b, DS/i] The parent is splittable and the current element is
			// inline in block context, or if the current element is a block
			// under a p-wrapper, split the tag stack.
			$this->trace( $inline ? 'insert DS/i' : 'insert CS/b' );
			$newRef = $this->splitTagStack( $newRef, $inline, $sourceStart );
			$parent = $newRef;
			$parentData = $parent->snData;
		} elseif ( $under && $parentData->needsPWrapping && $inline ) {
			// [A/i] If the element is inline and we are in body/blockquote,
			// we need to create a p-wrapper
			$this->trace( 'insert A/i' );
			$newRef = $this->insertPWrapper( $newRef, $sourceStart );
			$parent = $newRef;
			$parentData = $parent->snData;
		} elseif ( $parentData->ancestorPNode && !$inline ) {
			// [CU/b] If the element is non-inline and (despite attempting to
			// split above) there is still an ancestor p-wrap, disable that
			// p-wrap
			$this->trace( 'insert CU/b' );
			$this->disablePWrapper( $parent, $sourceStart );
		} else {
			// [A/b, B/i, C/i, D/b, DU/i] insert as normal
			$this->trace( 'insert normal' );
		}

		// An element with element children is a non-blank element
		$parentData->nonblankNodeCount++;

		// Insert the element downstream and so initialise its userData
		$this->serializer->insertElement( $preposition, $newRef,
			$element, $void, $sourceStart, $sourceLength );

		// Initialise snData
		if ( !$element->userData->snData ) {
			$elementData = $element->userData->snData = new RemexMungerData;
		} else {
			$elementData = $element->userData->snData;
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
	 * @param int $pos The source position
	 * @return SerializerNode
	 */
	private function splitTagStack( SerializerNode $parentNode, $inline, $pos ) {
		$parentData = $parentNode->snData;
		$wrapBase = $parentData->wrapBaseNode;
		$pWrap = $parentData->ancestorPNode;
		if ( !$pWrap ) {
			$cloneEnd = $wrapBase;
		} else {
			$cloneEnd = $parentData->ancestorPNode;
		}

		$serializer = $this->serializer;
		$node = $parentNode;
		$root = $serializer->getRootNode();
		$nodes = [];
		$removableNodes = [];
		while ( $node !== $cloneEnd ) {
			$nextParent = $serializer->getParentNode( $node );
			if ( $nextParent === $root ) {
				throw new \Exception( 'Did not find end of clone range' );
			}
			$nodes[] = $node;
			if ( $node->snData->nonblankNodeCount === 0 ) {
				$removableNodes[] = $node;
				$nextParent->snData->nonblankNodeCount--;
			}
			$node = $nextParent;
		}

		if ( $inline ) {
			$pWrap = $this->insertPWrapper( $wrapBase, $pos );
			$node = $pWrap;
		} else {
			if ( $pWrap ) {
				// End the p-wrap which was open, cancel the diversion
				$wrapBase->snData->childPElement = null;
			}
			$pWrap = null;
			$node = $wrapBase;
		}

		for ( $i = count( $nodes ) - 1; $i >= 0; $i-- ) {
			$oldNode = $nodes[$i];
			$oldData = $oldNode->snData;
			$nodeParent = $node;
			$element = new Element( $oldNode->namespace, $oldNode->name, $oldNode->attrs );
			$this->serializer->insertElement( TreeBuilder::UNDER, $nodeParent,
				$element, false, $pos, 0 );
			$oldData->currentCloneElement = $element;

			$newNode = $element->userData;
			$newData = $newNode->snData = new RemexMungerData;
			if ( $pWrap ) {
				$newData->ancestorPNode = $pWrap;
			}
			$newData->isSplittable = true;
			$newData->wrapBaseNode = $wrapBase;
			$newData->isPWrapper = $oldData->isPWrapper;

			$nodeParent->snData->nonblankNodeCount++;

			$node = $newNode;
		}
		foreach ( $removableNodes as $rNode ) {
			$fakeElement = new Element( $rNode->namespace, $rNode->name, $rNode->attrs );
			$fakeElement->userData = $rNode;
			$this->serializer->removeNode( $fakeElement, $pos );
		}
		return $node;
	}

	/**
	 * Find the ancestor of $node which is a child of a p-wrapper, and
	 * reparent that node so that it is placed after the end of the p-wrapper
	 * @param SerializerNode $node
	 * @param int $sourceStart
	 */
	private function disablePWrapper( SerializerNode $node, $sourceStart ) {
		$nodeData = $node->snData;
		$pWrapNode = $nodeData->ancestorPNode;
		$newParent = $this->serializer->getParentNode( $pWrapNode );
		if ( $pWrapNode !== $this->serializer->getLastChild( $newParent ) ) {
			// Fostering or something? Abort!
			return;
		}

		$nextParent = $node;
		do {
			$victim = $nextParent;
			$victim->snData->ancestorPNode = null;
			$nextParent = $this->serializer->getParentNode( $victim );
		} while ( $nextParent !== $pWrapNode );

		// Make a fake Element to use in a reparenting operation
		$victimElement = new Element( $victim->namespace, $victim->name, $victim->attrs );
		$victimElement->userData = $victim;

		// Reparent
		$this->serializer->insertElement( TreeBuilder::UNDER, $newParent, $victimElement,
			false, $sourceStart, 0 );

		// Decrement nonblank node count
		$pWrapNode->snData->nonblankNodeCount--;

		// Cancel the diversion so that no more elements are inserted under this p-wrap
		$newParent->snData->childPElement = null;
	}

	public function endTag( Element $element, $sourceStart, $sourceLength ) {
		$data = $element->userData->snData;
		if ( $data->childPElement ) {
			$this->endTag( $data->childPElement, $sourceStart, 0 );
		}
		$this->serializer->endTag( $element, $sourceStart, $sourceLength );
		$element->userData->snData = null;
		$element->userData = null;
	}

	public function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
		$this->serializer->doctype( $name, $public, $system, $quirks,
			$sourceStart, $sourceLength );
	}

	public function comment( $preposition, $refElement, $text, $sourceStart, $sourceLength ) {
		list( , $refNode ) = $this->getParentForInsert( $preposition, $refElement );
		$this->serializer->comment( $preposition, $refNode, $text, $sourceStart, $sourceLength );
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
		$self = $element->userData;
		if ( $self->snData->childPElement ) {
			// Reparent under the p-wrapper instead, so that e.g.
			//   <blockquote><mw:p-wrap>...</mw:p-wrap></blockquote>
			// becomes
			//   <blockquote><mw:p-wrap><i>...</i></mw:p-wrap></blockquote>

			// The formatting element should not be the parent of the p-wrap.
			// Without this special case, the insertElement() of the <i> below
			// would be diverted into the p-wrapper, causing infinite recursion
			// (T178632)
			$this->reparentChildren( $self->snData->childPElement, $newParent, $sourceStart );
			return;
		}

		$children = $self->children;
		$self->children = [];
		$this->insertElement( TreeBuilder::UNDER, $element, $newParent, false, $sourceStart, 0 );
		$newParentNode = $newParent->userData;
		$newParentId = $newParentNode->id;
		foreach ( $children as $child ) {
			if ( is_object( $child ) ) {
				$this->trace( "reparent <{$child->name}>" );
				$child->parentId = $newParentId;
			}
		}
		$newParentNode->children = $children;
	}
}
