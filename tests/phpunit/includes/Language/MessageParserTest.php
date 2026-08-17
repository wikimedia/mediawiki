<?php

use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parser;

/**
 * @covers \MediaWiki\Language\MessageParser
 * @group Database
 */
class MessageParserTest extends MediaWikiIntegrationTestCase {

	public function testNestedMessageParse() {
		$msgOuter = ( new RawMessage( '[[Link|{{#language:}}]]' ) )
			->inLanguage( 'outer' )
			->page( $this->makePage( 'Link' ) );

		// T372891: Allow nested message parsing
		// Any hook from Linker or LinkRenderer will do for this test, but this one is the simplest
		$this->setTemporaryHook( 'SelfLinkBegin', static function ( $nt, &$html, &$trail, &$prefix, &$ret ) {
			$msgInner = ( new RawMessage( '{{#language:}}' ) )->inLanguage( 'inner' );
			$html .= $msgInner->escaped();
		} );

		$this->assertEquals( '<a class="mw-selflink selflink">outerinner</a>', $msgOuter->parse() );
	}

	public function testNestedLimit() {
		$text = '[[Link|label]]';
		$messageParser = $this->getServiceContainer()->getMessageParser();
		$this->setTemporaryHook(
			'SelfLinkBegin',
			function ( $nt, &$html, &$trail, &$prefix, &$ret ) use ( $messageParser ) {
				$html .= $messageParser
					->parse( '[[Link|label]]', $this->makePage( 'Link' ), true, true, 'en' )
					->getContentHolderText();
			} );
		$result = $messageParser
			->parse( $text, $this->makePage( 'Link' ), true, true, 'en' )
			->getContentHolderText();
		$this->assertStringContainsString( 'Message parse depth limit exceeded', $result );

		// Check that the MessageParser is still functional after hitting the limit
		$result = $messageParser
			->parse( 'test', null, true, true, 'en' )
			->getContentHolderText();
		$this->assertSame( 'test', Parser::stripOuterParagraph( $result ) );
	}

	public static function provideGetLinkColours() {
		yield 'links' => [
			'config' => [
				MainConfigNames::UsePigLatinVariant => false,
			],
			'expectCalls' => 1,
			'expectPagemap' => [ [ 'Foo', 'Bar' ] ],
			'expectHtml' => '/class="test-class-Foo".*class="test-class-Bar"/',
		];
		yield 'links with language variant' => [
			'config' => [
				MainConfigNames::UsePigLatinVariant => true,
				MainConfigNames::DefaultLanguageVariant => 'en-x-piglatin',
				MainConfigNames::DisableLangConversion => false,
				MainConfigNames::DisabledVariants => [],
			],
			'expectCalls' => 2,
			'expectPagemap' => [ [ 'Foo', 'Bar' ], [ 'Uxquay' ] ],
			'expectHtml' => '/class="test-class-Foo".*class="test-class-Uxquay"/',
		];
	}

	/**
	 * @dataProvider provideGetLinkColours
	 */
	public function testGetLinkColoursAddsClassToLinks(
		array $config,
		int $expectCalls,
		array $expectPagemap,
		string $expectHtml
	) {
		$this->overrideConfigValues( $config );
		$this->getExistingTestPage( 'Foo' );
		$this->getExistingTestPage( 'Bar' );
		$this->getExistingTestPage( 'Uxquay' );
		$text = '[[Foo|label]] [[Bar]] [[Quux]]';

		$hookCalls = 0;
		$pagemapArg = [];
		$this->setTemporaryHook(
			'GetLinkColours',
			static function ( $pagemap, &$colours ) use ( &$hookCalls, &$pagemapArg ) {
				$hookCalls++;
				$pagemapArg[] = array_values( $pagemap );
				foreach ( $pagemap as $id => $pdbk ) {
					$colours[$pdbk] = 'test-class-' . $pdbk;
				}
			}
		);

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$result = $messageParser
			->parse( $text, $this->makePage( 'Main_Page' ), true, false, 'en' )
			->getContentHolderText();

		$this->assertSame( $expectPagemap, $pagemapArg, 'Pagemap arg' );
		$this->assertSame( $expectCalls, $hookCalls );
		$this->assertMatchesRegularExpression( $expectHtml, $result );
	}

	/**
	 * @dataProvider provideGetLinkColours
	 */
	public function testGetLinkColoursForInterfaceMessages( array $config ) {
		$this->overrideConfigValues( $config );
		$this->getExistingTestPage( 'Foo' );
		$this->getExistingTestPage( 'Bar' );
		$this->getExistingTestPage( 'Uxquay' );
		$text = '[[Foo|label]] [[Bar]] [[Quux]]';

		$hookCalls = 0;
		// T432883: Interface message parses should skip GetLinkColours, including
		// the second call made from LinkHolderArray::doVariants().
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$messageParser
			->parse( $text, $this->makePage( 'Main_Page' ), true, true, 'en' )
			->getContentHolderText();

		$this->assertSame( 0, $hookCalls );
	}

	public static function provideTransform() {
		return [
			[
				'test',
				[],
				'test'
			],
			[
				'{{PLURAL:21|one|more}}',
				[ 'lang' => 'en' ],
				'more'
			],
			[
				'{{PLURAL:21|one|more}}',
				[ 'lang' => 'be' ],
				'one'
			],
			[
				'{{PAGENAME}}',
				[],
				'Badtitle/MessageParser'
			],
			[
				'{{PAGENAME}}',
				[ 'page' => 'Main_Page' ],
				'Main Page',
			]
		];
	}

	/**
	 * @dataProvider provideTransform
	 */
	public function testTransform( $input, $options, $expected ) {
		$messageParser = $this->getServiceContainer()->getMessageParser();
		$result = $messageParser->transform(
			$input,
			$options['interface'] ?? true,
			$options['lang'] ?? 'en',
			$this->makePage( $options['page'] ?? null )
		);
		$this->assertSame( $expected, $result );
	}

	private function makePage( $title ) {
		return $title
			? PageIdentityValue::localIdentity( 1, NS_MAIN, $title )
			: null;
	}

	public static function provideParse() {
		return [
			[
				'test',
				[],
				'test'
			],
			[
				'* Bullet',
				[],
				'<ul><li>Bullet</li></ul>'
			],
			[
				'* Asterisk',
				[ 'lineStart' => false ],
				'* Asterisk'
			],
			[
				'{{#bcp47:}}',
				[ 'lang' => 'fr' ],
				'fr'
			],
		];
	}

	/**
	 * @dataProvider provideParse
	 * @param string $input
	 * @param array $options
	 * @param string $expected
	 */
	public function testParse( $input, $options, $expected ) {
		$messageParser = $this->getServiceContainer()->getMessageParser();
		$parserOutput = $messageParser->parse(
			$input,
			$this->makePage( $options['page'] ?? null ),
			$options['lineStart'] ?? true,
			$options['interface'] ?? true,
			$options['lang'] ?? 'en'
		);
		$result = Parser::stripOuterParagraph( $parserOutput->getContentHolderText() );
		$this->assertSame( $expected, $result );
	}
}
