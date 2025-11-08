<?php

use MediaWiki\Cache\GenderCache;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;

abstract class TitleCodecTestBase extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::AllowUserJs => false,
			MainConfigNames::DefaultLanguageVariant => false,
			MainConfigNames::MetaNamespace => 'Project',
			MainConfigNames::LocalInterwikis => [ 'localtestiw' ],
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::LanguageCode => 'en',
		] );
		$this->setUserLang( 'en' );
	}

	/**
	 * Returns a mock GenderCache that will consider a user "female" if the
	 * first part of the user name ends with "a".
	 *
	 * @return GenderCache
	 */
	protected function getGenderCache() {
		$genderCache = $this->createMock( GenderCache::class );

		$genderCache->method( 'getGenderOf' )
			->willReturnCallback( static function ( $userName ) {
				return preg_match( '/^[^- _]+a( |_|$)/u', $userName ) ? 'female' : 'male';
			} );

		return $genderCache;
	}

	/**
	 * Returns a InterwikiLookup where the only valid interwikis are 'localtestiw' and 'remotetestiw'.
	 * Only `isValidInterwiki` should actually be needed.
	 */
	protected function getInterwikiLookup(): InterwikiLookup {
		return $this->getDummyInterwikiLookup( [ 'localtestiw', 'remotetestiw' ] );
	}

	/**
	 * Returns a NamespaceInfo where the only namespaces that exist are NS_SPECIAL, NS_MAIN, NS_TALK,
	 * NS_USER, and NS_USER_TALK. As per the real NamespaceInfo, NS_USER and NS_USER_TALK have
	 * gender distinctions. All namespaces are capitalized.
	 */
	protected function getNamespaceInfo(): NamespaceInfo {
		return $this->getDummyNamespaceInfo( [
			MainConfigNames::CanonicalNamespaceNames => [
				NS_SPECIAL => 'Special',
				NS_MAIN => '',
				NS_TALK => 'Talk',
				NS_USER => 'User',
				NS_USER_TALK => 'User_talk',
			],
			MainConfigNames::CapitalLinks => true,
		] );
	}

	protected function makeParser( $lang ) {
		return new TitleParser(
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( $lang ),
			$this->getInterwikiLookup(),
			$this->getNamespaceInfo(),
			[ 'localtestiw' ]
		);
	}

	protected function makeFormatter( $lang ) {
		return new TitleFormatter(
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( $lang ),
			$this->getGenderCache(),
			$this->getNamespaceInfo()
		);
	}

}
