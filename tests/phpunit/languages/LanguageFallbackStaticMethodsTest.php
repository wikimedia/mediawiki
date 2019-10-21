<?php

/**
 * @coversDefaultClass Language
 */
class LanguageFallbackStaticMethodsTest extends MediaWikiIntegrationTestCase {
	use LanguageFallbackTestTrait;

	private function getCallee( array $options = [] ) {
		if ( isset( $options['siteLangCode'] ) ) {
			$this->setMwGlobals( 'wgLanguageCode', $options['siteLangCode'] );
			$this->resetServices();
		}
		return Language::class;
	}

	private function getMessagesKey() {
		return Language::MESSAGES_FALLBACKS;
	}

	private function getStrictKey() {
		return Language::STRICT_FALLBACKS;
	}
}
