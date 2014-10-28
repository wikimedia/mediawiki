<?php

class MessageKeyTest extends MediaWikiTestCase {
	/**
	 * Assert that there for each english message key a message key in the qqq file exists,
	 * which contains a message documentation for translators.
	 * It can also use templates from translatewiki.net
	 */
	public function testMessageKeys() {
		global $wgMessagesDirs;

		$lcache = Language::getLocalisationCache();
		$messageKeysEn = array();
		$messageKeysQqq = array();
		foreach ( $wgMessagesDirs as $dirs ) {
			foreach ( (array)$dirs as $dir ) {
				$dataEn = $lcache->readJSONFile( "$dir/en.json" );
				$messageKeysEn = array_merge( $messageKeysEn, $dataEn['messages'] );

				$dataQqq = $lcache->readJSONFile( "$dir/qqq.json" );
				$messageKeysQqq = array_merge( $messageKeysQqq, $dataQqq['messages'] );
			}
		}

		// Remove all the same keys to make the assertEquals output nicer/smaller
		foreach ( $messageKeysEn as $key => $data ) {
			if ( isset( $messageKeysQqq[$key] ) ) {
				unset( $messageKeysEn[$key] );
				unset( $messageKeysQqq[$key] );
			}
		}

		// Not using assertEmpty to get all the keys as a diff
		$this->assertEquals(
			array_keys( $messageKeysEn ),
			array_keys( $messageKeysQqq ),
			'Each english message should have a message documentation in language file \'qqq\''
		);
	}
}
