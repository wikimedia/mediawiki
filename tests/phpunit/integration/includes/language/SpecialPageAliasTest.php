<?php

use MediaWiki\Languages\LanguageNameUtils;
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
	/**
	 * @coversNothing
	 */
	public function testValidSpecialPageAliases() {
		$titleParser = $this->getServiceContainer()->getTitleParser();
		foreach ( $this->getValidSpecialPageAliases() as [ $languageCode, $specialPageAliases ] ) {
			foreach ( $specialPageAliases as $specialPage => $aliases ) {
				foreach ( $aliases as $alias ) {
					$msg = "\$specialPageAliases[$languageCode][$specialPage] → '$alias' ";

					$this->assertStringNotContainsString( '/', $alias, $msg .
						'must not contain slashes'
					);

					$this->assertNotNull( $titleParser->makeTitleValueSafe( NS_SPECIAL, $alias ), $msg .
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

	public function getValidSpecialPageAliases(): iterable {
		$languageNameUtils = $this->getServiceContainer()->getLanguageNameUtils();
		$langNames = $languageNameUtils->getLanguageNames(
			LanguageNameUtils::AUTONYMS,
			LanguageNameUtils::SUPPORTED
		);
		foreach ( $langNames as $code => $_ ) {
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
