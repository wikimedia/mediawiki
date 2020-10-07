<?php

use MediaWiki\MediaWikiServices;

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiIntegrationTestCase {
	protected function setUp() : void {
		global $wgLanguageCode;

		$services = MediaWikiServices::getInstance();
		$contLang = $services->getContentLanguage();
		if ( $wgLanguageCode != $contLang->getCode() ) {
			throw new MWException( "Error in MediaWikiLangTestCase::setUp(): " .
				"\$wgLanguageCode ('$wgLanguageCode') is different from content language code (" .
				$contLang->getCode() . ")" );
		}

		parent::setUp();

		$this->setUserLang( 'en' );
		// For mainpage to be 'Main Page'
		$this->setContentLang( 'en' );

		$services->getMessageCache()->disable();
	}
}
