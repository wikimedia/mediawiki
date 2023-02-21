<?php

namespace MediaWiki\Html;

use Wikimedia\Assert\Assert;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\HtmlFormatter;
use Wikimedia\RemexHtml\Serializer\Serializer;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\RemexHtml\Tokenizer\Tokenizer;
use Wikimedia\RemexHtml\TreeBuilder\Dispatcher;
use Wikimedia\RemexHtml\TreeBuilder\TreeBuilder;

/**
 * Static utilities for manipulating HTML strings.
 */
class HtmlHelper {

	/**
	 * Modify elements of an HTML fragment via a user-provided callback.
	 * @param string $htmlFragment HTML fragment. Must be valid (ie. coming from the parser, not
	 *   the user).
	 * @param callable $shouldModifyCallback A callback which takes a single
	 *   RemexHtml\Serializer\SerializerNode argument, and returns true if it should be modified.
	 * @param callable $modifyCallback A callback which takes a single
	 *   RemexHtml\Serializer\SerializerNode argument and actually performs the modification on it.
	 *   It must return the new node (which can be the original node object).
	 * @return string
	 */
	public static function modifyElements(
		string $htmlFragment,
		callable $shouldModifyCallback,
		callable $modifyCallback
	) {
		$formatter = new class( $options = [], $shouldModifyCallback, $modifyCallback ) extends HtmlFormatter {
			/** @var callable */
			private $shouldModifyCallback;

			/** @var callable */
			private $modifyCallback;

			public function __construct( $options, $shouldModifyCallback, $modifyCallback ) {
				parent::__construct( $options );
				$this->shouldModifyCallback = $shouldModifyCallback;
				$this->modifyCallback = $modifyCallback;
			}

			public function element( SerializerNode $parent, SerializerNode $node, $contents ) {
				if ( ( $this->shouldModifyCallback )( $node ) ) {
					$node = clone $node;
					$node->attrs = clone $node->attrs;
					$newNode = ( $this->modifyCallback )( $node );
					Assert::parameterType( SerializerNode::class, $newNode, 'return value' );
					return parent::element( $parent, $newNode, $contents );
				} else {
					return parent::element( $parent, $node, $contents );
				}
			}

			public function startDocument( $fragmentNamespace, $fragmentName ) {
				return '';
			}
		};
		$serializer = new Serializer( $formatter );
		$treeBuilder = new TreeBuilder( $serializer );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $htmlFragment );

		$tokenizer->execute( [
			'fragmentNamespace' => HTMLData::NS_HTML,
			'fragmentName' => 'body',
		] );

		return $serializer->getResult();
	}

}

class_alias( HtmlHelper::class, 'MediaWiki\\HtmlHelper' );
