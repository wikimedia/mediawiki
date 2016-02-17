<?php

/**
 * Verifies that special page aliases are valid, with no slashes.
 *
 * @group Language
 * @group SpecialPageAliases
 * @group SystemTest
 * @group medium
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageAliasTest extends MediaWikiTestCase {

	/**
	 * @dataProvider validSpecialPageAliasesProvider
	 */
	public function testValidSpecialPageAliases( $code, $specialPageAliases ) {
		foreach ( $specialPageAliases as $specialPage => $aliases ) {
			foreach ( $aliases as $alias ) {
				$msg = "$specialPage alias '$alias' in $code is valid with no slashes";
				$this->assertRegExp( '/^[^\/]*$/', $msg );
			}
		}
	}

	public function validSpecialPageAliasesProvider() {
		$codes = array_keys( Language::fetchLanguageNames( 'mwfile' ) );

		$data = [];

		foreach ( $codes as $code ) {
			$specialPageAliases = $this->getSpecialPageAliases( $code );

			if ( $specialPageAliases !== [] ) {
				$data[] = [ $code, $specialPageAliases ];
			}
		}

		return $data;
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

			if ( isset( $specialPageAliases ) && $specialPageAliases !== null ) {
				return $specialPageAliases;
			}
		}

		return [];
	}

}
