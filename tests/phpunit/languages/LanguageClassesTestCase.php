<?php

use MediaWiki\MediaWikiServices;

/**
 * Helping class to run tests using a clean language instance.
 *
 * This is intended for the MediaWiki language class tests under
 * tests/phpunit/languages.
 *
 * Before each tests, a new language object is build which you
 * can retrieve in your test using the $this->getLang() method:
 *
 * @par Using the crafted language object:
 * @code
 * function testHasLanguageObject() {
 *   $langObject = $this->getLang();
 *   $this->assertInstanceOf( 'LanguageFoo',
 *     $langObject
 *   );
 * }
 * @endcode
 */
abstract class LanguageClassesTestCase extends MediaWikiIntegrationTestCase {
	/**
	 * Internal language object
	 *
	 * A new object is created before each tests thanks to PHPUnit
	 * setUp() method, it is deleted after each test too. To get
	 * this object you simply use the getLang method.
	 *
	 * You must have setup a language code first. See $LanguageClassCode
	 * @code
	 *  function testWeAreTheChampions() {
	 *    $this->getLang();  # language object
	 *  }
	 * @endcode
	 */
	private $languageObject;

	/**
	 * @return Language
	 */
	protected function getLang() {
		return $this->languageObject;
	}

	/**
	 * Create a new language object before each test.
	 */
	protected function setUp() : void {
		parent::setUp();
		$found = preg_match( '/Language(.+)Test/', static::class, $m );
		if ( $found ) {
			# Normalize language code since classes uses underscores
			$m[1] = strtolower( str_replace( '_', '-', $m[1] ) );
		} else {
			# Fallback to english language
			$m[1] = 'en';
			wfDebug(
				__METHOD__ . ' could not extract a language name '
					. 'out of ' . static::class . " failling back to 'en'"
			);
		}
		// @todo validate $m[1] which should be a valid language code
		$this->languageObject = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $m[1] );
	}

	/**
	 * Delete the internal language object so each test start
	 * out with a fresh language instance.
	 */
	protected function tearDown() : void {
		unset( $this->languageObject );
		parent::tearDown();
	}
}
