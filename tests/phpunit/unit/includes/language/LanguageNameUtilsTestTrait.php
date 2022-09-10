<?php

// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag -- Traits are not excluded

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;

const AUTONYMS = LanguageNameUtils::AUTONYMS;
const ALL = LanguageNameUtils::ALL;
const DEFINED = LanguageNameUtils::DEFINED;
const SUPPORTED = LanguageNameUtils::SUPPORTED;

/**
 * For code shared between LanguageNameUtilsTest and LanguageTest.
 * @internal For use by LanguageNameUtilsTest and LanguageTest only.
 */
trait LanguageNameUtilsTestTrait {
	abstract protected function isSupportedLanguage( $code );

	/**
	 * @dataProvider provideIsSupportedLanguage
	 * @covers MediaWiki\Languages\LanguageNameUtils::__construct
	 * @covers MediaWiki\Languages\LanguageNameUtils::isSupportedLanguage
	 * @covers Language::isSupportedLanguage
	 */
	public function testIsSupportedLanguage( $code, $expected ) {
		$this->assertSame( $expected, $this->isSupportedLanguage( $code ) );
	}

	public static function provideIsSupportedLanguage() {
		return [
			'en' => [ 'en', true ],
			'fi' => [ 'fi', true ],
			'bunny' => [ 'bunny', false ],
			'qqq' => [ 'qqq', false ],
			'uppercase is not considered supported' => [ 'FI', false ],
		];
	}

	abstract protected function isValidCode( $code );

	/**
	 * We don't test that the result is cached, because that should only be noticeable if the
	 * configuration changes in between calls, and 1) that should never happen in normal operation,
	 * 2) if you do it you deserve whatever you get, and 3) once the static Language method is
	 * dropped and the invalid title regex is moved to something injected instead of a static call,
	 * the cache will be undetectable.
	 *
	 * @todo Should we test changes to $wgLegalTitleChars here? Does anybody actually change that?
	 * Is it possible to change it usefully without breaking everything?
	 *
	 * @dataProvider provideIsValidCode
	 * @covers MediaWiki\Languages\LanguageNameUtils::isValidCode
	 * @covers Language::isValidCode
	 *
	 * @param string $code
	 * @param bool $expected
	 */
	public function testIsValidCode( $code, $expected ) {
		$this->assertSame( $expected, $this->isValidCode( $code ) );
	}

	public static function provideIsValidCode() {
		$ret = [
			'en' => [ 'en', true ],
			'en-GB' => [ 'en-GB', true ],
			'Funny chars' => [ "%!$()*,-.;=?@^_`~\x80\xA2\xFF+", true ],
			'Percent escape not allowed' => [ 'a%aF', false ],
			'Percent with only one following char is okay' => [ '%a', true ],
			'Percent with non-hex following chars is okay' => [ '%AG', true ],
			'Named char reference "a"' => [ 'a&a', false ],
			'Named char reference "A"' => [ 'a&A', false ],
			'Named char reference "0"' => [ 'a&0', false ],
			'Named char reference non-ASCII' => [ "a&\x92", false ],
			'Numeric char reference' => [ "a&#0", false ],
			'Hex char reference 0' => [ "a&#x0", false ],
			'Hex char reference A' => [ "a&#xA", false ],
			'Lone ampersand is valid for title but not lang code' => [ '&', false ],
			'Ampersand followed by just # is valid for title but not lang code' => [ '&#', false ],
			'Ampersand followed by # and non-x/digit is valid for title but not lang code' =>
				[ '&#a', false ],
		];
		$disallowedChars = ":/\\\000&<>'\"";
		foreach ( str_split( $disallowedChars ) as $char ) {
			$ret["Disallowed character $char"] = [ "a{$char}a", false ];
		}
		return $ret;
	}

	abstract protected function isValidBuiltInCode( $code );

	/**
	 * @dataProvider provideIsValidBuiltInCode
	 * @covers MediaWiki\Languages\LanguageNameUtils::isValidBuiltInCode
	 * @covers Language::isValidBuiltInCode
	 *
	 * @param string $code
	 * @param bool $expected
	 */
	public function testIsValidBuiltInCode( $code, $expected ) {
		$this->assertSame( $expected, $this->isValidBuiltInCode( $code ) );
	}

	public static function provideIsValidBuiltInCode() {
		return [
			'Two letters, lowercase' => [ 'fr', true ],
			'Two letters, uppercase' => [ 'EN', false ],
			'Three letters' => [ 'tyv', true ],
			'With dash' => [ 'be-tarask', true ],
			'With extension (two dashes)' => [ 'be-x-old', true ],
			'Reject underscores' => [ 'be_tarask', false ],
			'One letter' => [ 'a', false ],
			'Only digits' => [ '00', true ],
			'Only dashes' => [ '--', true ],
			'Unreasonably long' => [ str_repeat( 'x', 100 ), true ],
			'qqq' => [ 'qqq', true ],
		];
	}

	abstract protected function isKnownLanguageTag( $code );

	/**
	 * @dataProvider provideIsKnownLanguageTag
	 * @covers MediaWiki\Languages\LanguageNameUtils::isKnownLanguageTag
	 * @covers Language::isKnownLanguageTag
	 *
	 * @param string $code
	 * @param bool $expected
	 */
	public function testIsKnownLanguageTag( $code, $expected ) {
		$this->assertSame( $expected, $this->isKnownLanguageTag( $code ) );
	}

	public static function provideIsKnownLanguageTag() {
		$invalidBuiltInCodes = array_filter( static::provideIsValidBuiltInCode(),
			static function ( $arr ) {
				// If isValidBuiltInCode() returns false, we want to also, but if it returns true,
				// we could still return false from isKnownLanguageTag(), so skip those.
				return !$arr[1];
			}
		);
		return array_merge( $invalidBuiltInCodes, [
			'Simple code' => [ 'fr', true ],
			'An MW legacy tag' => [ 'bat-smg', true ],
			'An internal standard MW name, for which a legacy tag is used externally' =>
				[ 'sgs', true ],
			'Non-existent two-letter code' => [ 'mw', false ],
			'Very invalid language code' => [ 'foo"<bar', false ],
		] );
	}

	abstract protected function assertGetLanguageNames(
		array $options, $expected, $code, ...$otherArgs
	);

	abstract protected function getLanguageNames( ...$args );

	abstract protected function getLanguageName( ...$args );

	/**
	 * @dataProvider provideGetLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames( $expected, $code, ...$otherArgs ) {
		$this->assertGetLanguageNames( [], $expected, $code, ...$otherArgs );
	}

	public static function provideGetLanguageNames() {
		// @todo There are probably lots of interesting tests to add here.
		return [
			'Simple code' => [ 'Deutsch', 'de' ],
			'Simple code in a different language (doesn\'t work without hook)' =>
				[ 'Deutsch', 'de', 'fr' ],
			'Invalid code' => [ '', '&' ],
			'Pig Latin not enabled' => [ '', 'en-x-piglatin', AUTONYMS, ALL ],
			'qqq doesn\'t have a name' => [ '', 'qqq', AUTONYMS, ALL ],
			'An MW legacy tag is recognized' => [ 'žemaitėška', 'bat-smg' ],
			// @todo Is the next test's result desired?
			'An MW legacy tag is not supported' => [ '', 'bat-smg', AUTONYMS, SUPPORTED ],
			'An internal standard name, for which a legacy tag is used externally, is supported' =>
				[ 'žemaitėška', 'sgs', AUTONYMS, SUPPORTED ],
		];
	}

	/**
	 * Set a temporary hook, allows using DI.
	 * @param string $hookName
	 * @param mixed $handler Value suitable for a hook handler
	 */
	abstract protected function setLanguageTemporaryHook( string $hookName, $handler ): void;

	/**
	 * @dataProvider provideGetLanguageNames_withHook
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames_withHook( $expected, $code, ...$otherArgs ) {
		$this->setLanguageTemporaryHook( 'LanguageGetTranslatedLanguageNames',
			static function ( &$names, $inLanguage ) {
				switch ( $inLanguage ) {
					case 'de':
						$names = [
							'de' => 'Deutsch',
							'en' => 'Englisch',
							'fr' => 'Französisch',
						];
						break;

					case 'en':
						$names = [
							'de' => 'German',
							'en' => 'English',
							'fr' => 'French',
							'sqsqsqsq' => '!!?!',
							'bat-smg' => 'Samogitian',
						];
						break;

					case 'fr':
						$names = [
							'de' => 'allemand',
							'en' => 'anglais',
							// Deliberate mistake (no cedilla)
							'fr' => 'francais',
						];
						break;
				}
			}
		);

		// Really we could dispense with assertGetLanguageNames() and just call
		// testGetLanguageNames() here, but it looks weird to call a test method from another test
		// method.
		$this->assertGetLanguageNames( [], $expected, $code, ...$otherArgs );
	}

	public static function provideGetLanguageNames_withHook() {
		return [
			'Simple code in a different language' => [ 'allemand', 'de', 'fr' ],
			'Invalid inLanguage defaults to English' => [ 'German', 'de', '&' ],
			'If inLanguage not provided, default to autonym' => [ 'Deutsch', 'de' ],
			'Hooks ignored for explicitly-requested autonym' => [ 'français', 'fr', 'fr' ],
			'Hooks don\'t make a language supported' => [ '', 'bat-smg', 'en', SUPPORTED ],
			'Hooks don\'t make a language defined' => [ '', 'sqsqsqsq', 'en', DEFINED ],
			'Hooks do make a language name returned with ALL' => [ '!!?!', 'sqsqsqsq', 'en', ALL ],
		];
	}

	/**
	 * @dataProvider provideGetLanguageNames_ExtraLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames_ExtraLanguageNames( $expected, $code, ...$otherArgs ) {
		$this->setLanguageTemporaryHook( 'LanguageGetTranslatedLanguageNames',
			static function ( &$names ) {
				$names['de'] = 'die deutsche Sprache';
			}
		);
		$this->assertGetLanguageNames(
			[ MainConfigNames::ExtraLanguageNames => [ 'de' => 'deutsche Sprache', 'sqsqsqsq' => '!!?!' ] ],
			$expected, $code, ...$otherArgs
		);
	}

	public function provideGetLanguageNames_ExtraLanguageNames() {
		return [
			'Simple extra language name' => [ '!!?!', 'sqsqsqsq' ],
			'Extra language is defined' => [ '!!?!', 'sqsqsqsq', AUTONYMS, DEFINED ],
			'Extra language is not supported' => [ '', 'sqsqsqsq', AUTONYMS, SUPPORTED ],
			'Extra language overrides default' => [ 'deutsche Sprache', 'de' ],
			'Extra language overrides hook for explicitly requested autonym' =>
				[ 'deutsche Sprache', 'de', 'de' ],
			'Hook overrides extra language for non-autonym' =>
				[ 'die deutsche Sprache', 'de', 'fr' ],
		];
	}

	/**
	 * Test that getLanguageNames() defaults to DEFINED, and getLanguageName() defaults to ALL.
	 *
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames_parameterDefault() {
		$this->setLanguageTemporaryHook( 'LanguageGetTranslatedLanguageNames',
			static function ( &$names ) {
				$names = [ 'sqsqsqsq' => '!!?!' ];
			}
		);

		// We use 'en' here because the hook is not run if we're requesting autonyms, although in
		// this case (language that isn't defined by MediaWiki itself) that behavior seems wrong.
		$this->assertArrayNotHasKey( 'sqsqsqsq', $this->getLanguageNames(), 'en' );

		$this->assertSame( '!!?!', $this->getLanguageName( 'sqsqsqsq', 'en' ) );
	}

	/**
	 * @dataProvider provideGetLanguageNames_sorted
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers Language::fetchLanguageNames
	 */
	public function testGetLanguageNames_sorted( ...$args ) {
		$names = $this->getLanguageNames( ...$args );
		$sortedNames = $names;
		ksort( $sortedNames );
		$this->assertSame( $sortedNames, $names );
	}

	public function provideGetLanguageNames_sorted() {
		return [
			[],
			[ AUTONYMS ],
			[ AUTONYMS, DEFINED ],
			[ AUTONYMS, ALL ],
			[ AUTONYMS, SUPPORTED ],
			[ 'he', DEFINED ],
			[ 'he', ALL ],
			[ 'he', SUPPORTED ],
		];
	}

	/**
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers Language::fetchLanguageNames
	 */
	public function testGetLanguageNames_hookNotCalledForAutonyms() {
		$count = 0;
		$this->setLanguageTemporaryHook( 'LanguageGetTranslatedLanguageNames',
			static function () use ( &$count ) {
				$count++;
			}
		);

		$this->getLanguageNames();
		$this->assertSame( 0, $count, 'Hook must not be called for autonyms' );

		// We test elsewhere that the hook works, but the following verifies that our test is
		// working and $count isn't being incremented above only because we're checking autonyms.
		$this->getLanguageNames( 'fr' );
		$this->assertSame( 1, $count, 'Hook must be called for non-autonyms' );
	}

	/**
	 * @dataProvider provideGetLanguageNames_pigLatin
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames_pigLatin( $expected, ...$otherArgs ) {
		$this->setLanguageTemporaryHook( 'LanguageGetTranslatedLanguageNames',
			static function ( &$names, $inLanguage ) {
				switch ( $inLanguage ) {
					case 'fr':
						$names = [ 'en-x-piglatin' => 'latin de cochons' ];
						break;

					case 'en-x-piglatin':
						// Deliberately lowercase
						$names = [ 'en-x-piglatin' => 'igpay atinlay' ];
						break;
				}
			}
		);

		$this->assertGetLanguageNames(
			[ MainConfigNames::UsePigLatinVariant => true ], $expected, 'en-x-piglatin', ...$otherArgs );
	}

	public function provideGetLanguageNames_pigLatin() {
		return [
			'Simple test' => [ 'Igpay Atinlay' ],
			'Not supported' => [ '', AUTONYMS, SUPPORTED ],
			'Foreign language' => [ 'latin de cochons', 'fr' ],
			'Hook doesn\'t override explicit autonym' =>
				[ 'Igpay Atinlay', 'en-x-piglatin', 'en-x-piglatin' ],
		];
	}

	/**
	 * Just for the sake of completeness, test that ExtraLanguageNames will not override the name
	 * for pig Latin. Nobody actually cares about this and if anything current behavior is probably
	 * wrong, but once we're testing the whole file we may as well be comprehensive.
	 *
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNames
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageNamesUncached
	 * @covers MediaWiki\Languages\LanguageNameUtils::getLanguageName
	 * @covers Language::fetchLanguageNames
	 * @covers Language::fetchLanguageName
	 */
	public function testGetLanguageNames_pigLatinAndExtraLanguageNames() {
		$this->assertGetLanguageNames(
			[
				MainConfigNames::UsePigLatinVariant => true,
				MainConfigNames::ExtraLanguageNames => [ 'en-x-piglatin' => 'igpay atinlay' ]
			],
			'Igpay Atinlay',
			'en-x-piglatin'
		);
	}

	abstract protected function getFileName( ...$args );

	/**
	 * @dataProvider provideGetFileName
	 * @covers MediaWiki\Languages\LanguageNameUtils::getFileName
	 * @covers Language::getFileName
	 */
	public function testGetFileName( $expected, ...$args ) {
		$this->assertSame( $expected, $this->getFileName( ...$args ) );
	}

	public function provideGetFileName() {
		return [
			'Simple case' => [ 'MessagesXx.php', 'Messages', 'xx' ],
			'With extension' => [ 'MessagesXx.ext', 'Messages', 'xx', '.ext' ],
			'Replacing dashes' => [ '!__?', '!', '--', '?' ],
			'Empty prefix and extension' => [ 'Xx', '', 'xx', '' ],
			'Uppercase only first letter' => [ 'Messages_a.php', 'Messages', '-a' ],
		];
	}

	abstract protected function getMessagesFileName( $code );

	/**
	 * @dataProvider provideGetMessagesFileName
	 * @covers MediaWiki\Languages\LanguageNameUtils::getMessagesFileName
	 * @covers Language::getMessagesFileName
	 *
	 * @param string $code
	 * @param string $expected
	 */
	public function testGetMessagesFileName( $code, $expected ) {
		$this->assertSame( $expected, $this->getMessagesFileName( $code ) );
	}

	public function provideGetMessagesFileName() {
		global $IP;
		return [
			'Simple case' => [ 'en', "$IP/languages/messages/MessagesEn.php" ],
			'Replacing dashes' => [ '--', "$IP/languages/messages/Messages__.php" ],
			'Uppercase only first letter' => [ '-a', "$IP/languages/messages/Messages_a.php" ],
		];
	}

	/**
	 * @covers MediaWiki\Languages\LanguageNameUtils::getMessagesFileName
	 * @covers Language::getMessagesFileName
	 */
	public function testGetMessagesFileName_withHook() {
		$called = 0;

		$this->setLanguageTemporaryHook( 'Language::getMessagesFileName',
			function ( $code, &$file ) use ( &$called ) {
				global $IP;

				$called++;

				$this->assertSame( 'ab-cd', $code );
				$this->assertSame( "$IP/languages/messages/MessagesAb_cd.php", $file );
				$file = 'bye-bye';
			}
		);

		$this->assertSame( 'bye-bye', $this->getMessagesFileName( 'ab-cd' ) );
		$this->assertSame( 1, $called );
	}

	abstract protected function getJsonMessagesFileName( $code );

	/**
	 * @covers MediaWiki\Languages\LanguageNameUtils::getJsonMessagesFileName
	 * @covers Language::getJsonMessagesFileName
	 */
	public function testGetJsonMessagesFileName() {
		global $IP;

		// Not so much to test here, one test seems to be enough
		$expected = "$IP/languages/i18n/en--123.json";
		$this->assertSame( $expected, $this->getJsonMessagesFileName( 'en--123' ) );
	}

	/**
	 * getFileName, getMessagesFileName, and getJsonMessagesFileName all throw if they get an
	 * invalid code. To save boilerplate, test them all in one method.
	 *
	 * @dataProvider provideExceptionFromInvalidCode
	 * @covers MediaWiki\Languages\LanguageNameUtils::getFileName
	 * @covers MediaWiki\Languages\LanguageNameUtils::getMessagesFileName
	 * @covers MediaWiki\Languages\LanguageNameUtils::getJsonMessagesFileName
	 * @covers Language::getFileName
	 * @covers Language::getMessagesFileName
	 * @covers Language::getJsonMessagesFileName
	 *
	 * @param callable $callback Will throw when passed $code
	 * @param string $code
	 */
	public function testExceptionFromInvalidCode( $callback, $code ) {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "Invalid language code \"$code\"" );

		$callback( $code );
	}

	public function provideExceptionFromInvalidCode() {
		$ret = [];
		foreach ( static::provideIsValidBuiltInCode() as $desc => list( $code, $valid ) ) {
			if ( $valid ) {
				// Won't get an exception from this one
				continue;
			}

			// For getFileName, we define an anonymous function because of the extra first param
			$ret["getFileName: $desc"] = [
				function ( $code ) {
					return $this->getFileName( 'Messages', $code );
				},
				$code
			];

			$ret["getMessagesFileName: $desc"] =
				[ [ $this, 'getMessagesFileName' ], $code ];

			$ret["getJsonMessagesFileName: $desc"] =
				[ [ $this, 'getJsonMessagesFileName' ], $code ];
		}
		return $ret;
	}
}
