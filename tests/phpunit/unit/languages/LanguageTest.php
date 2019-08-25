<?php

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Languages\LanguageFallback;

/**
 * @coversDefaultClass Language
 */
class LanguageTest extends MediaWikiUnitTestCase {
	/**
	 * @param array $options Valid keys:
	 *   'code'
	 *   'grammarTransformCache'
	 */
	private function getObj( array $options = [] ) {
		return new Language(
			$options['code'] ?? 'en',
			$this->createNoOpMock( LocalisationCache::class ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( LanguageFallback::class )
		);
	}

	/**
	 * @covers ::getGrammarTransformations
	 * @todo Test the exception case
	 */
	public function testGetGrammarTransformations() {
		global $IP;

		// XXX Inject a value here instead of reading the filesystem (T231159)
		$expected = FormatJson::decode(
			file_get_contents( "$IP/languages/data/grammarTransformations/he.json" ), true );

		$lang = $this->getObj( [ 'code' => 'he' ] );

		$this->assertSame( $expected, $lang->getGrammarTransformations() );
		$this->assertSame( $expected, $lang->getGrammarTransformations() );
	}

	/**
	 * @covers ::getGrammarTransformations
	 */
	public function testGetGrammarTransformations_empty() {
		$lang = $this->getObj();

		$this->assertSame( [], $lang->getGrammarTransformations() );
		$this->assertSame( [], $lang->getGrammarTransformations() );
	}
}
