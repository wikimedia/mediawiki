<?php

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiTestCase {
	private static $oldLang;
	private static $oldContLang;

	public function setUp() {
		global $wgLanguageCode, $wgLang, $wgContLang;

		self::$oldLang = $wgLang;
		self::$oldContLang = $wgContLang;

		if( $wgLanguageCode != $wgContLang->getCode() ) {
			throw new MWException("Error in MediaWikiLangTestCase::setUp(): " .
				"\$wgLanguageCode ('$wgLanguageCode') is different from " .
				"\$wgContLang->getCode() (" . $wgContLang->getCode() . ")" );
		}

		$wgLanguageCode = 'en'; # For mainpage to be 'Main Page'

		$wgContLang = $wgLang = Language::factory( $wgLanguageCode );
		MessageCache::singleton()->disable();
	}

	public function tearDown() {
		global $wgContLang, $wgLang, $wgLanguageCode;
		$wgLang = self::$oldLang;

		$wgContLang = self::$oldContLang;
		$wgLanguageCode = $wgContLang->getCode();
		self::$oldContLang = self::$oldLang = null;
	}

}
