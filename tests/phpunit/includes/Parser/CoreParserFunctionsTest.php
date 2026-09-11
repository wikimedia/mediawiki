<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\CoreParserFunctions;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiLangTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Parser\CoreParserFunctions
 */
class CoreParserFunctionsTest extends MediaWikiLangTestCase {

	public function testGender() {
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$username = 'Female*';
		$user = User::createNew( $username );
		$userOptionsManager->setOption( $user, 'gender', 'female' );
		$user->saveSettings();

		$msg = ( new RawMessage( '{{GENDER:' . $username . '|m|f|o}}' ) )->parse();
		$this->assertEquals( 'f', $msg, 'Works unescaped' );
		$escapedName = wfEscapeWikiText( $username );
		$msg2 = ( new RawMessage( '{{GENDER:' . $escapedName . '|m|f|o}}' ) )
			->parse();
		$this->assertEquals( 'f', $msg2, 'Works escaped' );
	}

	public static function provideTalkpagename() {
		yield [ 'Talk:Foo bar', 'foo_bar' ];
		yield [ 'Talk:Foo', ' foo ' ];
		yield [ 'Talk:Foo', 'Talk:Foo' ];
		yield [ 'User talk:Foo', 'User:foo' ];
		yield [ '', 'Special:Foo' ];
		yield [ '', '' ];
		yield [ '', ' ' ];
		yield [ '', '__' ];
		yield [ '', '#xyzzy' ];
		yield [ '', '#' ];
		yield [ '', ':' ];
		yield [ '', ':#' ];
		yield [ '', 'User:' ];
		yield [ '', 'User:#' ];
	}

	/**
	 * @dataProvider provideTalkpagename
	 */
	public function testTalkpagename( $expected, $title ) {
		$parser = $this->getServiceContainer()->getParser();

		$this->assertSame( $expected, CoreParserFunctions::talkpagename( $parser, $title ) );
	}

	public static function provideSubjectpagename() {
		yield [ 'Foo bar', 'Talk:foo_bar' ];
		yield [ 'Foo', ' Talk:foo ' ];
		yield [ 'User:Foo', 'User talk:foo' ];
		yield [ 'Special:Foo', 'Special:Foo' ];
		yield [ '', '' ];
		yield [ '', ' ' ];
		yield [ '', '__' ];
		yield [ '', '#xyzzy' ];
		yield [ '', '#' ];
		yield [ '', ':' ];
		yield [ '', ':#' ];
		yield [ '', 'Talk:' ];
		yield [ '', 'User talk:#' ];
		yield [ '', 'User:#' ];
	}

	/**
	 * @dataProvider provideSubjectpagename
	 */
	public function testSubjectpagename( $expected, $title ) {
		$parser = $this->getServiceContainer()->getParser();

		$this->assertSame( $expected, CoreParserFunctions::subjectpagename( $parser, $title ) );
	}

	public static function provideCategorysort() {
		yield 'timestamp' => [ '{{CATEGORYSORT:TIMESTAMP}}', 'timestamp' ];
		yield 'reverse timestamp' => [ '{{CATEGORYSORT:RTIMESTAMP}}', 'rtimestamp' ];
		yield 'case insensitive value' => [ '{{CATEGORYSORT:rtimestamp}}', 'rtimestamp' ];
		yield 'surrounding whitespace' => [ '{{CATEGORYSORT: TIMESTAMP }}', 'timestamp' ];
		yield 'last one wins' => [
			'{{CATEGORYSORT:TIMESTAMP}}{{CATEGORYSORT:RTIMESTAMP}}',
			'rtimestamp'
		];
		yield 'unknown value' => [ '{{CATEGORYSORT:sortkey}}', null ];
		yield 'empty value' => [ '{{CATEGORYSORT:}}', null ];
	}

	/**
	 * @dataProvider provideCategorysort
	 */
	public function testCategorysort( string $wikitext, ?string $expected ) {
		$title = Title::makeTitle( NS_CATEGORY, 'CoreParserFunctionsTest' );
		$parserOutput = $this->getServiceContainer()->getParserFactory()->getInstance()->parse(
			$wikitext,
			$title,
			ParserOptions::newFromAnon()
		);

		$this->assertSame( $expected, $parserOutput->getPageProperty( 'categorysort' ) );
		if ( $expected === null ) {
			$this->assertStringContainsString( 'class="error"', $parserOutput->getContentHolderText() );
		}
	}

	public function testGrammarRespectsGrammarFormsWithLeximorph(): void {
		$parser = $this->getMockBuilder( Parser::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getTargetLanguage', 'killMarkers' ] )
			->getMock();
		$parser->method( 'killMarkers' )->willReturnArgument( 0 );
		$targetLanguage = null;
		$parser->method( 'getTargetLanguage' )->willReturnCallback(
			static function () use ( &$targetLanguage ) {
				return $targetLanguage;
			}
		);

		$this->overrideConfigValue( MainConfigNames::GrammarForms, [
			'en' => [
				'genitive' => [
					'Project' => "Project's",
				],
			],
		] );

		$this->overrideConfigValue( MainConfigNames::UseLeximorph, false );
		$this->resetLeximorphLanguageServices();
		$targetLanguage = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$this->assertSame( "Project's", CoreParserFunctions::grammar( $parser, 'genitive', 'Project' ) );

		$this->overrideConfigValue( MainConfigNames::UseLeximorph, true );
		$this->resetLeximorphLanguageServices();
		$targetLanguage = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$this->assertSame( "Project's", CoreParserFunctions::grammar( $parser, 'genitive', 'Project' ) );
	}

	public function testPluralUsesLeximorphWhenEnabled(): void {
		$parser = $this->getMockBuilder( Parser::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getTargetLanguage' ] )
			->getMock();
		$forms = [ 'zero', 'one', 'two', 'few', 'many', 'other' ];
		$targetLanguage = null;
		$parser->method( 'getTargetLanguage' )->willReturnCallback(
			static function () use ( &$targetLanguage ) {
				return $targetLanguage;
			}
		);

		$this->overrideConfigValue( MainConfigNames::UseLeximorph, false );
		$this->resetLeximorphLanguageServices();
		$targetLanguage = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'ar' );
		$this->assertSame( 'few', CoreParserFunctions::plural( $parser, '3', ...$forms ) );

		$this->overrideConfigValue( MainConfigNames::UseLeximorph, true );
		$this->resetLeximorphLanguageServices();
		$targetLanguage = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'ar' );
		$this->assertSame( 'few', CoreParserFunctions::plural( $parser, '3', ...$forms ) );
	}

	private function resetLeximorphLanguageServices(): void {
		$services = $this->getServiceContainer();
		$services->resetServiceForTesting( 'LeximorphFactory' );
		$services->resetServiceForTesting( 'LanguageFactory' );
	}

}
