<?php

use MediaWiki\Languages\LanguageFallback;
use MediaWiki\MainConfigNames;

/**
 * @group Language
 * @covers \MediaWiki\Languages\LanguageFallback
 */
class LanguageFallbackIntegrationTest extends MediaWikiIntegrationTestCase {
	use LanguageFallbackTestTrait;

	private function getCallee( array $options = [] ): LanguageFallback {
		if ( isset( $options['siteLangCode'] ) ) {
			$this->overrideConfigValue( MainConfigNames::LanguageCode, $options['siteLangCode'] );
		}
		if ( isset( $options['fallbackMap'] ) ) {
			$this->setService( 'LocalisationCache', $this->getMockLocalisationCache(
				1, $options['fallbackMap'] ) );
		}
		return $this->getServiceContainer()->getLanguageFallback();
	}

}
