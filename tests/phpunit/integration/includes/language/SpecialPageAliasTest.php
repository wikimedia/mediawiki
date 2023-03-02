<?php

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use UtfNormal\Validator;

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
		foreach ( $this->validSpecialPageAliasesProvider() as [ $languageCode, $specialPageAliases ] ) {
			foreach ( $specialPageAliases as $specialPage => $aliases ) {
				foreach ( $aliases as $alias ) {
					$msg = "\$specialPageAliases[$languageCode][$specialPage] → '$alias' ";

					$this->assertStringNotContainsString( '/', $alias, $msg .
						'must not contain slashes'
					);

					$this->assertNotNull( Title::makeTitleSafe( NS_SPECIAL, $alias ), $msg .
						'is not a valid title'
					);

					$normalized = Validator::cleanUp( $alias );
					$this->assertSame( $normalized, $alias, $msg .
						'must be normalized UTF-8'
					);

					// Technically this is optional (see LocalisationCache::recache) but good practice
					if ( str_contains( $alias, ' ' ) ) {
						$this->addWarning( $msg .
							'should be in canonical DBkey form with underscores instead of spaces'
						);
					}
				}
			}
		}
	}

	/**
	 * @return Generator
	 */
	public function validSpecialPageAliasesProvider() {
		$languageNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();
		foreach ( self::$langNames as $code => $_ ) {
			$specialPageAliases = $this->getSpecialPageAliases( $languageNameUtils, $code );
			if ( $specialPageAliases ) {
				yield [ $code, $specialPageAliases ];
			}
		}
	}

	/**
	 * @param LanguageNameUtils $languageNameUtils
	 * @param string $code
	 *
	 * @return string[][]
	 */
	protected function getSpecialPageAliases( LanguageNameUtils $languageNameUtils, string $code ): array {
		$file = $languageNameUtils->getMessagesFileName( $code );

		if ( is_readable( $file ) ) {
			include $file;
			return $specialPageAliases ?? [];
		}

		return [];
	}

}
