<?php

use MediaWiki\Languages\LanguageFallback;
use MediaWiki\MainConfigNames;

/**
 * @group Language
 * @coversDefaultClass Language
 */
class LanguageFallbackStaticMethodsTest extends MediaWikiIntegrationTestCase {
	use LanguageFallbackTestTrait {
		callMethod as protected traitCallMethod;
	}

	private function getCallee( array $options = [] ) {
		if ( isset( $options['siteLangCode'] ) ) {
			$this->overrideConfigValue( MainConfigNames::LanguageCode, $options['siteLangCode'] );
		}
		if ( isset( $options['fallbackMap'] ) ) {
			$this->setService( 'LocalisationCache', $this->getMockLocalisationCache(
				1, $options['fallbackMap'] ) );
		}
		return Language::class;
	}

	private function callMethod( $callee, $method, ...$args ) {
		if ( $method === 'getFirst' ) {
			$method = 'getFallbackFor';
		} elseif ( $method === 'getAll' ) {
			$method = 'getFallbacksFor';
		} elseif ( $method === 'getAllIncludingSiteLanguage' ) {
			$method = 'getFallbacksIncludingSiteLanguage';
		}
		$this->hideDeprecated( 'Language::' . $method );
		return $this->traitCallMethod( $callee, $method, ...$args );
	}

	private function getMessagesKey() {
		return LanguageFallback::MESSAGES;
	}

	private function getStrictKey() {
		return LanguageFallback::STRICT;
	}
}
