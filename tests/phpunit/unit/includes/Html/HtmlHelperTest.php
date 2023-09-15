<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Html\HtmlHelper;
use MediaWikiUnitTestCase;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\RemexHtml\Tokenizer\PlainAttributes;

class HtmlHelperTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Html\HtmlHelper::modifyElements
	 */
	public function testModifyElements() {
		$shouldModifyCallback = static function ( SerializerNode $node ) {
			return $node->namespace === HTMLData::NS_HTML
				&& $node->name === 'a'
				&& isset( $node->attrs['href'] );
		};
		$modifyCallbackInPlace = static function ( SerializerNode $node ) {
			$node->attrs['href'] = 'https://tracker.org/' . $node->attrs['href'];
			return $node;
		};
		$input = '<p>Foo <a href="https://example.org/">bar</a> baz</p>';
		$expectedOutput = '<p>Foo <a href="https://tracker.org/https://example.org/">bar</a> baz</p>';

		$output = HtmlHelper::modifyElements( $input, $shouldModifyCallback, $modifyCallbackInPlace );
		$this->assertSame( $expectedOutput, $output );

		$modifyCallbackNew = static function ( SerializerNode $node ) {
			$href = 'https://tracker.org/' . $node->attrs['href'];
			$newNode = new SerializerNode( $node->id, $node->parentId, $node->namespace, $node->name,
				new PlainAttributes( [ 'href' => $href ] ), $node->void );
			$node->attrs['href'] = 'https://tracker.org/' . $node->attrs['href'];
			return $newNode;
		};

		$output = HtmlHelper::modifyElements( $input, $shouldModifyCallback, $modifyCallbackNew );
		$this->assertSame( $expectedOutput, $output );
	}

}
