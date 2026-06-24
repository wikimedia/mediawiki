<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Interwiki\Interwiki;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Helpers\SkinLanguageHelper;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Skin\Helpers\SkinLanguageHelper
 */
class SkinLanguageHelperTest extends MediaWikiIntegrationTestCase {

	public function testGetData() {
		$this->overrideConfigValues( [
			MainConfigNames::HideInterlanguageLinks => false,
			MainConfigNames::InterlanguageLinkCodeMap => [ 'talk' => 'fr' ],
			MainConfigNames::LanguageCode => 'qqx',
		] );

		$title = Title::makeTitle( NS_MAIN, 'Test' );

		$mockOutputPage = $this->createMock( OutputPage::class );

		$fakeContext = new RequestContext();
		$fakeContext->setTitle( $title );
		$fakeContext->setOutput( $mockOutputPage );
		$fakeContext->setLanguage( 'en' );

		$hookContainer = $this->createMock( HookContainer::class );
		$this->setService( 'HookContainer', $hookContainer );

		$mockIwLookup = $this->createMock( InterwikiLookup::class );
		$mockIwLookup->method( 'isValidInterwiki' )->willReturn( true );
		$mockIwLookup->method( 'fetch' )->willReturnCallback( static function ( string $prefix ) {
			return new Interwiki(
				$prefix,
				"https://$prefix.example.com/$1"
			);
		} );
		$this->setService( 'InterwikiLookup', $mockIwLookup );

		$languageLinks = [ 'en:Foo', 'talk:Page' ];

		$helper = new SkinLanguageHelper(
			$title,
			$fakeContext->getLanguage(),
			$fakeContext,
			$mockOutputPage,
			$languageLinks,
			$this->getServiceContainer()->getMainConfig()
		);

		$this->assertSame( [
			[
				'href' => 'https://en.example.com/Foo',
				'text' => 'English',
				'title' => 'Foo – English',
				'class' => 'interlanguage-link interwiki-en',
				'link-class' => 'interlanguage-link-target',
				'lang' => 'en',
				'hreflang' => 'en',
				'data-title' => 'Foo',
				'data-language-autonym' => 'English',
				'data-language-local-name' => 'English',
			],
			[
				'href' => 'https://talk.example.com/Page',
				'text' => 'Français',
				'title' => 'Page – français',
				'class' => 'interlanguage-link interwiki-talk',
				'link-class' => 'interlanguage-link-target',
				'lang' => 'fr',
				'hreflang' => 'fr',
				'data-title' => 'Page',
				'data-language-autonym' => 'Français',
				'data-language-local-name' => 'français',
			],
		], $helper->getData() );
	}

	/**
	 * @see https://phabricator.wikimedia.org/T294695
	 */
	public function testGetDataTurkishCapitalization() {
		$this->overrideConfigValues( [
			MainConfigNames::HideInterlanguageLinks => false,
			MainConfigNames::InterlanguageLinkCodeMap => [],
			MainConfigNames::LanguageCode => 'tr',
		] );

		$title = Title::makeTitle( NS_MAIN, 'Test' );

		$mockOutputPage = $this->createMock( OutputPage::class );

		$fakeContext = new RequestContext();
		$fakeContext->setTitle( $title );
		$fakeContext->setOutput( $mockOutputPage );
		// Set user interface language to Turkish
		$fakeContext->setLanguage( 'tr' );

		$hookContainer = $this->createMock( HookContainer::class );
		$this->setService( 'HookContainer', $hookContainer );

		$mockIwLookup = $this->createMock( InterwikiLookup::class );
		$mockIwLookup->method( 'isValidInterwiki' )->willReturn( true );
		$mockIwLookup->method( 'fetch' )->willReturnCallback( static function ( string $prefix ) {
			return new Interwiki(
				$prefix,
				"https://$prefix.example.com/$1"
			);
		} );
		$this->setService( 'InterwikiLookup', $mockIwLookup );

		$languageLinks = [ 'it:Roma' ];

		$helper = new SkinLanguageHelper(
			$title,
			$fakeContext->getLanguage(),
			$fakeContext,
			$mockOutputPage,
			$languageLinks,
			$this->getServiceContainer()->getMainConfig()
		);
		$languages = $helper->getData();
		$this->assertCount( 1, $languages );
		// T294695: Must be 'Italiano' (standard I), not 'İtaliano' (Turkish İ)
		$this->assertSame( 'Italiano', $languages[0]['text'] );
		$this->assertSame( 'Italiano', $languages[0]['data-language-autonym'] );
	}
}
