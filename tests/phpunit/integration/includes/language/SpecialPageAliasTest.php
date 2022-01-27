<?php

use MediaWiki\Languages\LanguageNameUtils;

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
	 * FIXME: Cannot access MW services in a dataProvider.
	 *
	 * @return Generator
	 */
	public function validSpecialPageAliasesProvider() {
		$codes = array_keys( $this->getServiceContainer()
				->getLanguageNameUtils()
				->getLanguageNames( LanguageNameUtils::AUTONYMS, LanguageNameUtils::SUPPORTED ) );

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
