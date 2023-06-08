<?php

use MediaWiki\MediaWikiServices;

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiIntegrationTestCase {
	/**
	 * The annotation causes this to be called immediately before setUp()
	 * @before
	 */
	final protected function mediaWikiLangSetUp(): void {
		global $wgLanguageCode;

		$services = MediaWikiServices::getInstance();
		$contLang = $services->getContentLanguage();
		if ( $wgLanguageCode != $contLang->getCode() ) {
			throw new RuntimeException( "Error in " . __METHOD__ . ': ' .
				"\$wgLanguageCode ('$wgLanguageCode') is different from content language code (" .
				$contLang->getCode() . ")" );
		}

		$this->setUserLang( 'en' );
		// For mainpage to be 'Main Page'
		$this->setContentLang( 'en' );

		$services->getMessageCache()->disable();
	}
}
