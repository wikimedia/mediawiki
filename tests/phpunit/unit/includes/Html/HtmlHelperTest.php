<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Html\HtmlHelper;
use MediaWikiUnitTestCase;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\RemexHtml\Tokenizer\PlainAttributes;

/**
 * @covers \MediaWiki\Html\HtmlHelper
 */
class HtmlHelperTest extends MediaWikiUnitTestCase {

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

		// Check the "legacy compatibility" mode, for void elements like <link>
		$input = "<link data-test='<style data-mw-deduplicate=\"&nbsp;\"&gt;bar</style&gt;'>";
		$shouldModifyCallback = static function ( SerializerNode $node ) {
			return false;
		};
		// HTML5 output
		$expectedOutput = '<link data-test="<style data-mw-deduplicate=&quot;&nbsp;&quot;>bar</style>">';
		$output = HtmlHelper::modifyElements( $input, $shouldModifyCallback, $modifyCallbackInPlace, true );
		$this->assertSame( $expectedOutput, $output );

		// "Legacy" output
		$expectedOutput = '<link data-test="<style data-mw-deduplicate=&quot;&#160;&quot;&gt;bar</style&gt;" />';
		$output = HtmlHelper::modifyElements( $input, $shouldModifyCallback, $modifyCallbackInPlace, false );
		$this->assertSame( $expectedOutput, $output );
	}

}
