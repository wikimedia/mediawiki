<?php

namespace MediaWiki\Tests\Unit\Rest;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Rest\MarkdownFormatter;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\MarkdownFormatter
 */
class MarkdownFormatterTest extends MediaWikiUnitTestCase {
	protected function setUp(): void {
		parent::setUp();

		// HACK: We rely on Sanitizer, which needs config in global variables.
		// The FragmentMode decides how non-ascii characters are encoded in
		// section IDs.
		$GLOBALS['wgFragmentMode'] = MainConfigSchema::getDefaultValue(
			MainConfigNames::FragmentMode
		);
	}

	private function newFormatter( string $innerOutput ): MarkdownFormatter {
		$inner = $this->createMock( ITextFormatter::class );
		$inner->method( 'format' )->willReturn( $innerOutput );
		$inner->method( 'getLangCode' )->willReturn( 'qqx' );

		return new MarkdownFormatter( $inner, '/wiki/$1' );
	}

	public function testGetLangCode() {
		$this->assertSame( 'qqx', $this->newFormatter( '' )->getLangCode() );
	}

	public static function provideWikitext() {
		yield 'plain text is unchanged' => [ 'Just some text.', 'Just some text.' ];
		yield 'internal link' => [ '[[Foo bar]]', '[Foo bar](/wiki/Foo_bar)' ];
		yield 'internal link with section' => [ '[[Foo#bar]]', '[Foo#bar](/wiki/Foo#bar)' ];
		yield 'internal link with hash in section' => [ '[[Foo#bar#bar]]', '[Foo#bar#bar](/wiki/Foo#bar#bar)' ];
		yield 'internal link with space in section' => [ '[[Foo#bar bar]]', '[Foo#bar bar](/wiki/Foo#bar_bar)' ];
		yield 'internal link with label' => [ '[[Foo|the foo]]', '[the foo](/wiki/Foo)' ];
		yield 'internal link with label and section' => [ '[[Foo#bar|the foo]]', '[the foo](/wiki/Foo#bar)' ];
		yield 'internal link with parentheses' => [
			'[[Foo (bar)]]',
			'[Foo (bar)](/wiki/Foo_%28bar%29)'
		];
		yield 'external link with label' => [
			'[https://example.org the label]',
			'[the label](https://example.org)'
		];
		yield 'external link without label' => [
			'[https://example.org]',
			'<https://example.org>'
		];
		yield 'external link with section' => [
			'[https://example.org#Something]',
			'<https://example.org#Something>'
		];
		yield 'naked URL' => [ 'See https://example.org/x now', 'See <https://example.org/x> now' ];
		yield 'naked URL with section' => [ 'See https://example.org/x#Foo now', 'See <https://example.org/x#Foo> now' ];
		yield 'naked URL before sentence punctuation' => [
			'See https://example.org/x.',
			'See <https://example.org/x>.'
		];
		yield 'bold, italic and both' => [
			"'''bold''' ''italic'' '''''both'''''",
			'**bold** *italic* ***both***'
		];
		yield 'mixed' => [
			"The ''[[Foo]]'' page, see [https://example.org docs].",
			'The *[Foo](/wiki/Foo)* page, see [docs](https://example.org).'
		];
	}

	/**
	 * @dataProvider provideWikitext
	 */
	public function testFormat_wikitext( string $wikitext, string $expected ) {
		$formatter = $this->newFormatter( $wikitext );

		$this->assertSame( $expected, $formatter->format( new MessageValue( 'some-message' ) ) );
	}

	/**
	 * @dataProvider provideWikitext
	 */
	public function testFormat_isIdempotent( string $wikitext, string $markdown ) {
		// The essential assertion: converting the converted output again
		// must not change it.
		$formatter = $this->newFormatter( $markdown );

		$this->assertSame( $markdown, $formatter->format( new MessageValue( 'some-message' ) ) );
	}

	public static function provideMarkdown() {
		yield 'link' => [ '[label](https://example.org)' ];
		yield 'link with URL as label' => [ '[https://example.org](https://example.org)' ];
		yield 'link to page' => [ '[Foo](/wiki/Foo)' ];
		yield 'wikitext-style link followed by parenthesis' => [ '[[Foo]](/wiki/Foo)' ];
		yield 'autolink' => [ '<https://example.org>' ];
		yield 'emphasis' => [ '**bold** *italic* ***both***' ];
	}

	/**
	 * @dataProvider provideMarkdown
	 */
	public function testFormat_markdownIsNotMangled( string $markdown ) {
		$formatter = $this->newFormatter( $markdown );

		$this->assertSame( $markdown, $formatter->format( new MessageValue( 'some-message' ) ) );
	}

	public function testFormat_mdKeySkipsConversion() {
		// Messages with a .md key are already Markdown, so wikitext-looking
		// content must pass through verbatim.
		$formatter = $this->newFormatter( "''not italic'' [[Foo]]" );

		$this->assertSame(
			"''not italic'' [[Foo]]",
			$formatter->format( new MessageValue( 'some-message.md' ) )
		);
	}

}
