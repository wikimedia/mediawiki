<?php

namespace MediaWiki\Tests\Parser;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWikiIntegrationTestCase;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Sanitizer
 * @covers \MediaWiki\Parser\Sanitizer
 */
class SanitizerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideHtml5Tags
	 * @param string $tag Name of an HTML5 element (ie: 'video')
	 * @param bool $escaped Whether sanitizer let the tag in or escape it (ie: '&lt;video&gt;')
	 */
	public function testInternalRemoveHtmlTagsOnHtml5Tags( $tag, $escaped ) {
		if ( $escaped ) {
			$this->assertEquals( "&lt;$tag&gt;",
				Sanitizer::internalRemoveHtmlTags( "<$tag>" )
			);
		} else {
			$this->assertEquals( "<$tag></$tag>\n",
				Sanitizer::internalRemoveHtmlTags( "<$tag></$tag>\n" )
			);
		}
	}

	/**
	 * @dataProvider provideHtml5Tags
	 * @param string $tag Name of an HTML5 element (ie: 'video')
	 * @param bool $escaped Whether sanitizer let the tag in or escape it (ie: '&lt;video&gt;')
	 */
	public function testRemoveSomeTagsOnHtml5Tags( $tag, $escaped ) {
		if ( $escaped ) {
			$this->assertEquals( "&lt;$tag&gt;",
				Sanitizer::removeSomeTags( "<$tag>" )
			);
		} else {
			$this->assertEquals( "<$tag></$tag>\n",
				Sanitizer::removeSomeTags( "<$tag></$tag>\n" )
			);
			$this->assertEquals( "<$tag></$tag>",
				Sanitizer::removeSomeTags( "<$tag>" )
			);
		}
	}

	public static function provideHtml5Tags() {
		$ESCAPED = true; # We want tag to be escaped
		$VERBATIM = false; # We want to keep the tag
		return [
			[ 'data', $VERBATIM ],
			[ 'mark', $VERBATIM ],
			[ 'time', $VERBATIM ],
			[ 'video', $ESCAPED ],
		];
	}

	public function dataRemoveHTMLtags() {
		return [
			// former testSelfClosingTag
			[
				'<div>Hello world</div />',
				'<div>Hello world</div>',
				'Self-closing closing div'
			],
			// Make sure special nested HTML5 semantics are not broken
			// https://html.spec.whatwg.org/multipage/semantics.html#the-kbd-element
			[
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'Nested <kbd>.'
			],
			// https://html.spec.whatwg.org/multipage/semantics.html#the-sub-and-sup-elements
			[
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'Nested <var>.'
			],
			// https://html.spec.whatwg.org/multipage/semantics.html#the-dfn-element
			[
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<abbr> inside <dfn>',
			],
		];
	}

	/**
	 * @dataProvider dataRemoveHTMLtags
	 */
	public function testInternalRemoveHTMLtags( $input, $output, $msg = null ) {
		$this->assertEquals( $output, Sanitizer::internalRemoveHtmlTags( $input ), $msg );
	}

	/**
	 * @dataProvider dataRemoveHTMLtags
	 */
	public function testRemoveSomeTags( $input, $output, $msg = null ) {
		$this->assertEquals( $output, Sanitizer::removeSomeTags( $input ), $msg );
	}

	/**
	 * @dataProvider provideDeprecatedAttributes
	 */
	public function testDeprecatedAttributesUnaltered( $inputAttr, $inputEl, $message = '' ) {
		$this->assertEquals( " $inputAttr",
			Sanitizer::fixTagAttributes( $inputAttr, $inputEl ),
			$message
		);
	}

	public static function provideDeprecatedAttributes() {
		/** [ <attribute>, <element>, [message] ] */
		return [
			[ 'clear="left"', 'br' ],
			[ 'clear="all"', 'br' ],
			[ 'width="100"', 'td' ],
			[ 'nowrap="true"', 'td' ],
			[ 'nowrap=""', 'td' ],
			[ 'align="right"', 'td' ],
			[ 'align="center"', 'table' ],
			[ 'align="left"', 'tr' ],
			[ 'align="center"', 'div' ],
			[ 'align="left"', 'h1' ],
			[ 'align="left"', 'p' ],
		];
	}

	/**
	 * @dataProvider provideValidateTagAttributes
	 */
	public function testValidateTagAttributes( $element, $attribs, $expected ) {
		$actual = Sanitizer::validateTagAttributes( $attribs, $element );
		$this->assertArrayEquals( $expected, $actual, false, true );
	}

	public static function provideValidateTagAttributes() {
		return [
			[ 'math',
				[ 'id' => 'foo bar', 'bogus' => 'stripped', 'data-foo' => 'bar' ],
				[ 'id' => 'foo_bar', 'data-foo' => 'bar' ],
			],
			[ 'meta',
				[ 'id' => 'foo bar', 'itemprop' => 'foo', 'content' => 'bar' ],
				[ 'itemprop' => 'foo', 'content' => 'bar' ],
			],
			[ 'div',
				[ 'role' => 'presentation', 'aria-hidden' => 'true' ],
				[ 'role' => 'presentation', 'aria-hidden' => 'true' ],
			],
			[ 'div',
				[ 'role' => 'menuitem', 'aria-hidden' => 'false' ],
				[ 'role' => 'menuitem', 'aria-hidden' => 'false' ],
			],
		];
	}

	/**
	 * @dataProvider provideAttributesAllowed
	 */
	public function testAttributesAllowedInternal( $element, $attribs ) {
		$sanitizer = TestingAccessWrapper::newFromClass( Sanitizer::class );
		$actual = $sanitizer->attributesAllowedInternal( $element );
		$this->assertArrayEquals( $attribs, array_keys( $actual ) );
	}

	public static function provideAttributesAllowed() {
		/** [ <element>, [ <good attribute 1>, <good attribute 2>, ...] ] */
		return [
			[ 'math', [ 'class', 'style', 'id', 'title' ] ],
			[ 'meta', [ 'itemprop', 'content' ] ],
			[ 'link', [ 'itemprop', 'href', 'title' ] ],
		];
	}

	/**
	 * @dataProvider provideEscapeIdForStuff
	 * @param string $stuff
	 * @param string[] $config
	 * @param string $id
	 * @param string|false $expected
	 * @param int|null $mode
	 */
	public function testEscapeIdForStuff( $stuff, array $config, $id, $expected, $mode = null ) {
		$func = "Sanitizer::escapeIdFor{$stuff}";
		$iwFlavor = array_pop( $config );
		$this->overrideConfigValues( [
			MainConfigNames::FragmentMode => $config,
			MainConfigNames::ExternalInterwikiFragmentMode => $iwFlavor,
		] );
		$escaped = $func( $id, $mode );
		self::assertEquals( $expected, $escaped );
	}

	public static function provideEscapeIdForStuff() {
		// Test inputs and outputs
		$text = 'foo тест_#%!\'()[]:<>&&amp;&amp;amp;%F0';
		$legacyEncoded = 'foo_.D1.82.D0.B5.D1.81.D1.82_.23.25.21.27.28.29.5B.5D:.3C.3E' .
			'.26.26amp.3B.26amp.3Bamp.3B.25F0';
		$html5EncodedId = 'foo_тест_#%!\'()[]:<>&&amp;&amp;amp;%F0';
		$html5EncodedHref = 'foo_тест_#%!\'()[]:<>&&amp;&amp;amp;%25F0';

		// Settings: last element is $wgExternalInterwikiFragmentMode, the rest is $wgFragmentMode
		$legacy = [ 'legacy', 'legacy' ];
		$legacyNew = [ 'legacy', 'html5', 'legacy' ];
		$newLegacy = [ 'html5', 'legacy', 'legacy' ];
		$new = [ 'html5', 'legacy' ];
		$allNew = [ 'html5', 'html5' ];

		return [
			// Pure legacy: how MW worked before 2017
			[ 'Attribute', $legacy, $text, $legacyEncoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $legacy, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $legacy, $text, $legacyEncoded ],
			[ 'ExternalInterwiki', $legacy, $text, $legacyEncoded ],

			// Transition to a new world: legacy links with HTML5 fallback
			[ 'Attribute', $legacyNew, $text, $legacyEncoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $legacyNew, $text, $html5EncodedId, Sanitizer::ID_FALLBACK ],
			[ 'Link', $legacyNew, $text, $legacyEncoded ],
			[ 'ExternalInterwiki', $legacyNew, $text, $legacyEncoded ],

			// New world: HTML5 links, legacy fallbacks
			[ 'Attribute', $newLegacy, $text, $html5EncodedId, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $newLegacy, $text, $legacyEncoded, Sanitizer::ID_FALLBACK ],
			[ 'Link', $newLegacy, $text, $html5EncodedHref ],
			[ 'ExternalInterwiki', $newLegacy, $text, $legacyEncoded ],

			// Distant future: no legacy fallbacks, but still linking to leagacy wikis
			[ 'Attribute', $new, $text, $html5EncodedId, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $new, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $new, $text, $html5EncodedHref ],
			[ 'ExternalInterwiki', $new, $text, $legacyEncoded ],

			// Just before the heat death of universe: external interwikis are also HTML5 \m/
			[ 'Attribute', $allNew, $text, $html5EncodedId, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $allNew, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $allNew, $text, $html5EncodedHref ],
			[ 'ExternalInterwiki', $allNew, $text, $html5EncodedHref ],

			// Whitespace
			[ 'attribute', $allNew, "foo bar", 'foo_bar', Sanitizer::ID_PRIMARY ],
			[ 'attribute', $allNew, "foo\fbar", 'foo_bar', Sanitizer::ID_PRIMARY ],
			[ 'attribute', $allNew, "foo\nbar", 'foo_bar', Sanitizer::ID_PRIMARY ],
			[ 'attribute', $allNew, "foo\tbar", 'foo_bar', Sanitizer::ID_PRIMARY ],
			[ 'attribute', $allNew, "foo\rbar", 'foo_bar', Sanitizer::ID_PRIMARY ],
		];
	}

	public function testInvalidFragmentThrows() {
		$this->overrideConfigValue( MainConfigNames::FragmentMode, [ 'boom!' ] );
		$this->expectException( InvalidArgumentException::class );
		Sanitizer::escapeIdForAttribute( 'This should throw' );
	}

	public function testNoPrimaryFragmentModeThrows() {
		$this->overrideConfigValue( MainConfigNames::FragmentMode, [ 666 => 'html5' ] );
		$this->expectException( UnexpectedValueException::class );
		Sanitizer::escapeIdForAttribute( 'This should throw' );
	}

	public function testNoPrimaryFragmentModeThrows2() {
		$this->overrideConfigValue( MainConfigNames::FragmentMode, [ 666 => 'html5' ] );
		$this->expectException( UnexpectedValueException::class );
		Sanitizer::escapeIdForLink( 'This should throw' );
	}

	/**
	 * Test escapeIdReferenceListInternal for consistency with escapeIdForAttribute
	 *
	 * @dataProvider provideEscapeIdReferenceListInternal
	 */
	public function testEscapeIdReferenceListInternal( $referenceList, $id1, $id2 ) {
		$sanitizer = TestingAccessWrapper::newFromClass( Sanitizer::class );
		$actual = $sanitizer->escapeIdReferenceListInternal( $referenceList );

		$this->assertEquals(
			$actual,
			Sanitizer::escapeIdForAttribute( $id1 )
			. ' '
			. Sanitizer::escapeIdForAttribute( $id2 )
		);
	}

	public static function provideEscapeIdReferenceListInternal() {
		/** [ <reference list>, <individual id 1>, <individual id 2> ] */
		return [
			[ 'foo bar', 'foo', 'bar' ],
			[ '#1 #2', '#1', '#2' ],
			[ '+1 +2', '+1', '+2' ],
		];
	}

	/**
	 * @dataProvider provideCleanUrl
	 */
	public function testCleanUrl( string $input, string $output ) {
		$this->assertEquals( $output, Sanitizer::cleanUrl( $input ) );
	}

	public static function provideCleanUrl() {
		return [
			[ 'http://www.example.com/file.txt', 'http://www.example.com/file.txt' ],
			[
				"https://www.exa\u{00AD}\u{200B}\u{2060}\u{FEFF}" .
				"\u{034F}\u{180B}\u{180C}\u{180D}\u{200C}\u{200D}" .
				"\u{FE00}\u{FE08}\u{FE0F}mple.com",
				'https://www.example.com'
			],
		];
	}

}
