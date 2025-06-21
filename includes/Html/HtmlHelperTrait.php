<?php

namespace MediaWiki\Html;

use Wikimedia\Assert\Assert;
use Wikimedia\RemexHtml\Serializer\HtmlFormatter;
use Wikimedia\RemexHtml\Serializer\SerializerNode;

/**
 * Internal helper trait for HtmlHelper::modifyHtml.
 *
 * This is designed to extend a HtmlFormatter.
 *
 * @phan-file-suppress PhanTraitParentReference
 */
trait HtmlHelperTrait {
	/** @var callable */
	private $shouldModifyCallback;

	/** @var callable */
	private $modifyCallback;

	public function __construct( array $options, callable $shouldModifyCallback, callable $modifyCallback ) {
		parent::__construct( $options );
		$this->shouldModifyCallback = $shouldModifyCallback;
		$this->modifyCallback = $modifyCallback;
		// Escape U+0338 (T387130)
		'@phan-var HtmlFormatter $this';
		$this->textEscapes["\u{0338}"] = '&#x338;';
	}

	public function element( SerializerNode $parent, SerializerNode $node, $contents ) {
		if ( ( $this->shouldModifyCallback )( $node ) ) {
			$node = clone $node;
			$node->attrs = clone $node->attrs;
			$newNode = ( $this->modifyCallback )( $node );
			Assert::parameterType( [ SerializerNode::class, 'string' ], $newNode, 'return value' );
			if ( is_string( $newNode ) ) {
				// Replace this element with an "outerHTML" string.
				return $newNode;
			}
			return parent::element( $parent, $newNode, $contents );
		} else {
			return parent::element( $parent, $node, $contents );
		}
	}

	public function startDocument( $fragmentNamespace, $fragmentName ) {
		return '';
	}
}
