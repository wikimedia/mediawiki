<?php

use MediaWiki\Languages\LanguageFallback;
use MediaWiki\MainConfigNames;

/**
 * @coversDefaultClass MediaWiki\Languages\LanguageFallback
 * @covers ::__construct
 */
class LanguageFallbackIntegrationTest extends MediaWikiIntegrationTestCase {
	use LanguageFallbackTestTrait;

	private function getCallee( array $options = [] ) {
		if ( isset( $options['siteLangCode'] ) ) {
			$this->overrideConfigValue( MainConfigNames::LanguageCode, $options['siteLangCode'] );
		}
		if ( isset( $options['fallbackMap'] ) ) {
			$this->setService( 'LocalisationCache', $this->getMockLocalisationCache(
				1, $options['fallbackMap'] ) );
		}
		return $this->getServiceContainer()->getLanguageFallback();
	}

	private function getMessagesKey() {
		return LanguageFallback::MESSAGES;
	}

	private function getStrictKey() {
		return LanguageFallback::STRICT;
	}
}
