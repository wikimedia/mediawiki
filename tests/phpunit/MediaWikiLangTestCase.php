<?php

use MediaWiki\MainConfigNames;

/**
 * Base class that store and restore the Language objects
 */
abstract class MediaWikiLangTestCase extends MediaWikiIntegrationTestCase {
	/**
	 * The annotation causes this to be called immediately before setUp()
	 * @before
	 */
	final protected function mediaWikiLangSetUp(): void {
		$services = $this->getServiceContainer();
		$languageCode = $this->getConfVar( MainConfigNames::LanguageCode );
		$contLanguageCode = $services->getContentLanguage()->getCode();
		if ( $languageCode !== $contLanguageCode ) {
			throw new RuntimeException( "Error in " . __METHOD__ . ': ' .
				"\$wgLanguageCode ('$languageCode') is different from content language code " .
				"('$contLanguageCode')" );
		}

		$this->setUserLang( 'en' );
		// For mainpage to be 'Main Page'
		$this->setContentLang( 'en' );

		$services->getMessageCache()->disable();
	}
}
