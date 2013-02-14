<?php

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiTestCase {

	protected function setUp() {
		global $wgLanguageCode, $wgContLang;
		parent::setUp();

		if ( $wgLanguageCode != $wgContLang->getCode() ) {
			throw new MWException( "Error in MediaWikiLangTestCase::setUp(): " .
				"\$wgLanguageCode ('$wgLanguageCode') is different from " .
				"\$wgContLang->getCode() (" . $wgContLang->getCode() . ")" );
		}

		$langCode = 'en'; # For mainpage to be 'Main Page'
		$langObj = Language::factory( $langCode );

		$this->setMwGlobals( array(
			'wgLanguageCode' => $langCode,
			'wgLang' => $langObj,
			'wgContLang' => $langObj,
		) );

		MessageCache::singleton()->disable();
	}
}
