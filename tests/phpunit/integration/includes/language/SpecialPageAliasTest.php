<?php

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MediaWikiServices;

/**
 * Verifies that special page aliases are valid, with no slashes.
 *
 * @group Language
 * @group SpecialPageAliases
 * @group SystemTest
 * @group medium
 * @todo This should be a structure test
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageAliasTest extends MediaWikiIntegrationTestCase {
	/** @var ?array Cache language names */
	private static $langNames = null;

	/**
	 * @throws Exception
	 */
	public static function setUpBeforeClass(): void {
		if ( !self::$langNames ) {
			$langNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();
			self::$langNames = $langNameUtils->getLanguageNames(
				LanguageNameUtils::AUTONYMS,
				LanguageNameUtils::SUPPORTED
			);
		}
	}

	/** @return void */
	public static function tearDownAfterClass(): void {
		self::$langNames = null;
	}

	/**
	 * @coversNothing
	 */
	public function testValidSpecialPageAliases() {
		foreach ( $this->validSpecialPageAliasesProvider() as $expected ) {
			$code = $expected[0];
			$specialPageAliases = $expected[1];
			foreach ( $specialPageAliases as $specialPage => $aliases ) {
				foreach ( $aliases as $alias ) {
					$msg = "Special:$specialPage alias '$alias' in $code must not contain slashes";
					$this->assertStringNotContainsString( '/', $alias, $msg );
				}
			}
		}
	}

	/**
	 * @return Generator
	 */
	public function validSpecialPageAliasesProvider() {
		$codes = array_keys( self::$langNames );

		foreach ( $codes as $code ) {
			$specialPageAliases = $this->getSpecialPageAliases( $code );

			if ( $specialPageAliases !== [] ) {
				yield [ $code, $specialPageAliases ];
			}
		}
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	protected function getSpecialPageAliases( $code ) {
		$file = Language::getMessagesFileName( $code );

		if ( is_readable( $file ) ) {
			include $file;
			return $specialPageAliases ?? [];
		}

		return [];
	}

}
