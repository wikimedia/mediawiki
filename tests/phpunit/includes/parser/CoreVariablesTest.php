<?php

/**
 * @group Database
 * @group Parser
 */
class CoreVariablesTest extends MediaWikiLangTestCase {
	public static function provideBadNames() {
		return [ [ "FOO<BAR" ], [ "FOO>BAR" ], [ "FOO\nBAR" ], [ "FOO\rBAR" ] ];
	}

	/**
	 * @dataProvider provideBadNames
	 * @expectedException MWException
	 * @covers Parser::setVariableHook
	 */
	public function testBadVariableHooks( $variable ) {
		global $wgParserConf, $wgContLang;
		$parser = new Parser( $wgParserConf );

		$parser->setVariableHook( $variable, [ $this, 'variablesCallback' ] );
		$parser->parse(
			"Foo{{" . $variable . "}}Baz",
			Title::newFromText( 'Test' ),
			ParserOptions::newFromUserAndLang( new User, $wgContLang )
		);
		$this->fail( 'Exception not thrown.' );
	}

	function variablesCallback( $parser ) {
		return 'One';
	}

	/**
	 * Wikitext snippet calling variables
	 */
	function provideVariableValues() {
		return [
			[ '{{!}}', '|' ],
			[ '{{CURRENTMONTH}}', '01' ],
			[ '{{CURRENTMONTH1}}', '1' ],
			[ '{{CURRENTMONTHNAME}}', 'January' ],
			[ '{{CURRENTMONTHNAMEGEN}}', 'January' ],
			[ '{{CURRENTMONTHABBREV}}', 'Jan' ],
			[ '{{CURRENTDAY}}', '15' ],
			[ '{{CURRENTDAY2}}', '15' ],
			[ '{{LOCALMONTH}}', '01' ],
			[ '{{LOCALMONTH1}}', '1' ],
			[ '{{LOCALMONTHNAME}}', 'January' ],
			[ '{{LOCALMONTHNAMEGEN}}', 'January' ],
			[ '{{LOCALMONTHABBREV}}', 'Jan' ],
			[ '{{LOCALDAY}}', '15' ],
			[ '{{LOCALDAY2}}', '15' ],
			[ '{{PAGENAME}}', 'CoreVariablesTest' ],
			[ '{{PAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{FULLPAGENAME}}', 'CoreVariablesTest' ],
			[ '{{FULLPAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{SUBPAGENAME}}', 'CoreVariablesTest' ],
			[ '{{SUBPAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{ROOTPAGENAME}}', 'CoreVariablesTest' ],
			[ '{{ROOTPAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{BASEPAGENAME}}', 'CoreVariablesTest' ],
			[ '{{BASEPAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{TALKPAGENAME}}', 'Talk:CoreVariablesTest' ],
			[ '{{TALKPAGENAMEE}}', 'Talk:CoreVariablesTest' ],
			[ '{{SUBJECTPAGENAME}}', 'CoreVariablesTest' ],
			[ '{{SUBJECTPAGENAMEE}}', 'CoreVariablesTest' ],
			[ '{{PAGEID}}', '123' ],
			[ '{{REVISIONID}}', '123' ],
			[ '{{REVISIONDAY}}', '15' ],
			[ '{{REVISIONDAY2}}', '15' ],
			[ '{{REVISIONMONTH}}', '1' ],
			[ '{{REVISIONMONTH1}}', '1' ],
			[ '{{REVISIONYEAR}}', '2001' ],
			[ '{{REVISIONTIMESTAMP}}', '20010115123456' ],
			[ '{{REVISIONUSER}}', 'UTSysop' ],
			[ '{{REVISIONSIZE}}', '7' ],
			[ '{{NAMESPACE}}', '' ],
			[ '{{NAMESPACEE}}', '' ],
			[ '{{NAMESPACENUMBER}}', '0' ],
			[ '{{TALKSPACE}}', 'Talk' ],
			[ '{{TALKSPACEE}}', 'Talk' ],
			[ '{{SUBJECTSPACE}}', '' ],
			[ '{{SUBJECTSPACEE}}', '' ],
			[ '{{CURRENTDAYNAME}}', 'Monday' ],
			[ '{{CURRENTYEAR}}', '2001' ],
			[ '{{CURRENTTIME}}', '12:34' ],
			[ '{{CURRENTHOUR}}', '12' ],
			[ '{{CURRENTWEEK}}', '3' ],
			[ '{{CURRENTDOW}}', '1' ],
			[ '{{LOCALDAYNAME}}', 'Monday' ],
			[ '{{LOCALYEAR}}', '2001' ],
			[ '{{LOCALTIME}}', '12:34' ],
			[ '{{LOCALHOUR}}', '12' ],
			[ '{{LOCALWEEK}}', '3' ],
			[ '{{LOCALDOW}}', '1' ],
			[ '{{NUMBEROFARTICLES}}', '0' ],
			[ '{{NUMBEROFFILES}}', '0' ],
			// Too dynamic inside tests
			// [ '{{NUMBEROFUSERS}}', '1' ],
			[ '{{NUMBEROFACTIVEUSERS}}', '-1' ],
			[ '{{NUMBEROFPAGES}}', '1' ],
			[ '{{NUMBEROFADMINS}}', '1' ],
			// [ '{{NUMBEROFEDITS}}', '1' ],
			[ '{{CURRENTTIMESTAMP}}', '20010115123456' ],
			[ '{{LOCALTIMESTAMP}}', '20010115123456' ],
			// has extra Git hash - makes test hard
			// [ '{{CURRENTVERSION}}', 'test' ],
			[ '{{ARTICLEPATH}}', '/wiki/' ],
			[ '{{SITENAME}}', 'Sitename' ],
			[ '{{SERVER}}', 'localhost' ],
			[ '{{SERVERNAME}}', 'localhost' ],
			[ '{{SCRIPTPATH}}', '/' ],
			[ '{{STYLEPATH}}', '/skins' ],
			[ '{{DIRECTIONMARK}}', Sanitizer::decodeCharReferences( '&lrm;' ) ],
			[ '{{CONTENTLANGUAGE}}', 'en' ],
			[ '{{CASCADINGSOURCES}}', '' ],
		];
	}

	/**
	 * @dataProvider provideVariableValues
	 * @covers CoreVariables
	 */
	function testVariableValue( $snippet, $expected ) {
		$timestamp = '2001-01-15T12:34:56Z';
		$title = Title::makeTitle( NS_MAIN, 'CoreVariablesTest' );
		$pageid = 123;
		$revid = 123;

		$this->setMwGlobals( [
			'wgHooks' => [
				'ParserGetVariableValueTs' => [
					function ( &$parser, &$time ) use ( $timestamp ) {
						$time = $timestamp;
					}
				]
			],
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/',
			'wgStylePath' => '/skins',
			'wgServer' => 'localhost',
			'wgServerName' => 'localhost',
			'wgSitename' => 'Sitename',
			'wgVersion' => 'test',
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );

		global $wgParserConf, $wgContLang;
		$title->resetArticleID( $pageid );
		$parser = new Parser( $wgParserConf );
		$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		$options->setCurrentRevisionCallback(
			function ( $titleToCheck, $parser = false ) use ( $title, $timestamp, $pageid, $revid ) {
				$user = User::newFromName( 'UTSysop' );
				return new Revision( [
					'id' => $revid,
					'page' => $pageid,
					'user_text' => $user->getName(),
					'user' => $user->getId(),
					'title' => $title,
					'content' => new WikiTextContent( 'Content' ),
					'timestamp' => $timestamp,
				] );
			}
		);

		$parserOutput = $parser->parse(
			$snippet,
			$title,
			$options,
			true,
			true,
			$revid
		);

		$this->assertEquals(
			$expected,
			Parser::stripOuterParagraph( $parserOutput->getText() )
		);
	}
}
