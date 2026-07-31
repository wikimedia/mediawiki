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

	public function testGetLinkColoursAddsClassToLinks() {
		$this->getExistingTestPage( 'Link' );
		$text = '[[Link|label]]';

		$this->setTemporaryHook(
			'GetLinkColours',
			static function ( $pagemap, &$colours ) {
				foreach ( $colours as $pdbk => $class ) {
					$colours[$pdbk] = trim( "$class test-class" );
				}
			}
		);

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$result = $messageParser
			->parse( $text, $this->makePage( 'Main_Page' ), true, false, 'en' )
			->getContentHolderText();

		$this->assertStringContainsString( 'class="test-class"', $result );
	}

	public function testGetLinkColoursForInterfaceMessages() {
		$this->getExistingTestPage( 'Link' );
		$text = '[[Link|label]]';

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$hookCalls = 0;
		// T432883: Interface message parses should skip GetLinkColours.
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser->parse(
			$text,
			$this->makePage( 'Main_Page' ),
			true,
			true,
			'en'
		);

		$this->assertSame( 0, $hookCalls );
	}

	public function testGetLinkColoursForInterfaceMessagesWithTwoLinks() {
		$this->getExistingTestPage( 'Link' );
		$this->getExistingTestPage( 'Link2' );
		$text = '[[Link|label]][[Link2|label]]';

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$hookCalls = 0;
		// T432883: Interface message parses should skip GetLinkColours.
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser->parse(
			$text,
			$this->makePage( 'Main_Page' ),
			true,
			true,
			'en'
		);

		$this->assertSame( 0, $hookCalls );
	}

	public function testGetLinkColoursForInterfaceMessagesWithVariants() {
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'zh',
			MainConfigNames::DefaultLanguageVariant => 'zh-tw',
			MainConfigNames::DisableLangConversion => false,
			MainConfigNames::DisabledVariants => [],
		] );

		$this->getExistingTestPage( '傌' );
		$text = '[[zh:㐷|label]]';

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$hookCalls = 0;
		// T432883: Interface message parses should skip GetLinkColours, including
		// the second call made from LinkHolderArray::doVariants().
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser->parse(
			$text,
			$this->makePage( 'Main_Page' ),
			true,
			true,
			'zh'
		);

		$this->assertSame( 0, $hookCalls );
	}

	public function testGetLinkColoursForNonInterfaceMessages() {
		$this->getExistingTestPage( 'Link' );
		$text = '[[Link|label]]';

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$hookCalls = 0;
		// T432883: Non-interface message parses should still call GetLinkColours.
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser->parse(
			$text,
			$this->makePage( 'Main_Page' ),
			true,
			false,
			'en'
		);

		$this->assertSame( 1, $hookCalls );
	}

	public function testGetLinkColoursForNonInterfaceMessagesWithVariants() {
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'zh',
			MainConfigNames::DefaultLanguageVariant => 'zh-tw',
			MainConfigNames::DisableLangConversion => false,
			MainConfigNames::DisabledVariants => [],
		] );

		$this->getExistingTestPage( '傌' );
		$this->getExistingTestPage( 'Link' );
		$text = '[[Link|label]][[zh:㐷|label]]';

		$messageParser = $this->getServiceContainer()->getMessageParser();
		$hookCalls = 0;
		// T432883: Interface message parses should skip GetLinkColours, including
		// the second call made from LinkHolderArray::doVariants().
		$this->setTemporaryHook(
			'GetLinkColours',
			static function () use ( &$hookCalls ) {
				$hookCalls++;
			}
		);

		$messageParser->parse(
			$text,
			$this->makePage( 'Main_Page' ),
			true,
			false,
			'zh'
		);

		$this->assertSame( 2, $hookCalls );
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
