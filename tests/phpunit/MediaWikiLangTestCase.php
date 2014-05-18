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

		// HACK: Call getLanguage() so the real $wgContLang is cached as the user language
		// rather than our fake one. This is to avoid breaking other, unrelated tests.
		RequestContext::getMain()->getLanguage();

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
