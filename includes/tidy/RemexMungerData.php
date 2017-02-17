<?php

namespace MediaWiki\Tidy;

/**
 * @internal
 */
class RemexMungerData {
	/** @var \RemexHtml\TreeBuilder\Element|null */
	public $childPElement;

	/** @var \RemexHtml\Serializer\SerializerNode|null */
	public $ancestorPNode;

	/** @var \RemexHtml\Serializer\SerializerNode|null */
	public $wrapBaseNode;

	/** @var \RemexHtml\TreeBuilder\Element|null */
	public $currentCloneElement;

	public $isPWrapper = false;
	public $isSplittable = false;
	public $needsPWrapping = false;
	public $hasText = false;
	public $nonblankNodeCount = 0;
	public $isInBlock = false;

	public function __set( $name, $value ) {
		throw new \Exception( "Cannot set property \"$name\"" );
	}
}
