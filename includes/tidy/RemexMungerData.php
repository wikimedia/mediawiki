<?php

namespace MediaWiki\Tidy;

/**
 * @internal
 */
class RemexMungerData {
	/**
	 * The Element for the mw:p-wrap which is a child of the current node. If
	 * this is set, inline insertions into this node will be diverted so that
	 * they insert into the p-wrap.
	 *
	 * @var \RemexHtml\TreeBuilder\Element|null
	 */
	public $childPElement;

	/**
	 * This tracks the mw:p-wrap node in the Serializer stack which is an
	 * ancestor of this node. If there is no mw:p-wrap ancestor, it is null.
	 *
	 * @var \RemexHtml\Serializer\SerializerNode|null
	 */
	public $ancestorPNode;

	/**
	 * The wrap base node is the body or blockquote node which is the parent
	 * of active p-wrappers. This is set if there is an ancestor p-wrapper,
	 * or if a p-wrapper was closed due to a block element being encountered
	 * inside it.
	 *
	 * @var \RemexHtml\Serializer\SerializerNode|null
	 */
	public $wrapBaseNode;

	/**
	 * Stack splitting (essentially our idea of AFE reconstruction) can clone
	 * formatting elements which are split over multiple paragraphs.
	 * TreeBuilder is not aware of the cloning, and continues to insert into
	 * the original element. This is set to the newer clone if this node was
	 * cloned, i.e. if there is an active diversion of the insertion location.
	 *
	 * @var \RemexHtml\TreeBuilder\Element|null
	 */
	public $currentCloneElement;

	/**
	 * Is the node a p-wrapper, with name mw:p-wrap?
	 *
	 * @var bool
	 */
	public $isPWrapper = false;

	/**
	 * Is the node splittable, i.e. a formatting element or a node with a
	 * formatting element ancestor which is under an active or deactivated
	 * p-wrapper.
	 *
	 * @var bool
	 */
	public $isSplittable = false;

	/**
	 * This is true if the node is a body or blockquote, which activates
	 * p-wrapping of child nodes.
	 */
	public $needsPWrapping = false;

	/**
	 * The number of child nodes, not counting whitespace-only text nodes or
	 * comments.
	 */
	public $nonblankNodeCount = 0;

	public function __set( $name, $value ) {
		throw new \Exception( "Cannot set property \"$name\"" );
	}

	/**
	 * Get a text representation of the current state of the serializer, for
	 * debugging.
	 *
	 * @return string
	 */
	public function dump() {
		$parts = [];

		if ( $this->childPElement ) {
			$parts[] = 'childPElement=' . $this->childPElement->getDebugTag();
		}
		if ( $this->ancestorPNode ) {
			$parts[] = "ancestorPNode=<{$this->ancestorPNode->name}>";
		}
		if ( $this->wrapBaseNode ) {
			$parts[] = "wrapBaseNode=<{$this->wrapBaseNode->name}>";
		}
		if ( $this->currentCloneElement ) {
			$parts[] = "currentCloneElement=" . $this->currentCloneElement->getDebugTag();
		}
		if ( $this->isPWrapper ) {
			$parts[] = 'isPWrapper';
		}
		if ( $this->isSplittable ) {
			$parts[] = 'isSplittable';
		}
		if ( $this->needsPWrapping ) {
			$parts[] = 'needsPWrapping';
		}
		if ( $this->nonblankNodeCount ) {
			$parts[] = "nonblankNodeCount={$this->nonblankNodeCount}";
		}
		$s = "RemexMungerData {\n";
		foreach ( $parts as $part ) {
			$s .= "  $part\n";
		}
		$s .= "}\n";
		return $s;
	}
}
