<?php

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiTestCase {
	protected function setUp() {
		global $wgLanguageCode, $wgContLang;

		if ( $wgLanguageCode != $wgContLang->getCode() ) {
			throw new MWException( "Error in MediaWikiLangTestCase::setUp(): " .
				"\$wgLanguageCode ('$wgLanguageCode') is different from " .
				"\$wgContLang->getCode() (" . $wgContLang->getCode() . ")" );
		}

		parent::setUp();

		$this->setUserLang( 'en' );
		// For mainpage to be 'Main Page'
		$this->setContentLang( 'en' );

		MessageCache::singleton()->disable();
	}
}
