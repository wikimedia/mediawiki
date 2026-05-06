<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Interwiki\Interwiki;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Helpers\SkinLanguageHelper;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Skin\Helpers\SkinLanguageHelper
 * @group Database
 */
class SkinLanguageHelperTest extends MediaWikiIntegrationTestCase {

	public function testGetData() {
		$this->overrideConfigValues( [
			MainConfigNames::HideInterlanguageLinks => false,
			MainConfigNames::InterlanguageLinkCodeMap => [ 'talk' => 'fr' ],
			MainConfigNames::LanguageCode => 'qqx',
		] );

		$mockOutputPage = $this->createMock( OutputPage::class );
		$mockOutputPage->method( 'getLanguageLinks' )
			// The 'talk' interwiki is a deliberate conflict with the
			// Talk namespace (T363538)
			->willReturn( [ 'en:Foo', 'talk:Page' ] );

		$fakeContext = new RequestContext();
		$fakeContext->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
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

		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$skin->setContext( $fakeContext );

		$helper = new SkinLanguageHelper( $skin );

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

		$mockOutputPage = $this->createMock( OutputPage::class );
		$mockOutputPage->method( 'getLanguageLinks' )
			->willReturn( [ 'it:Roma' ] );

		$fakeContext = new RequestContext();
		$fakeContext->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
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

		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$skin->setContext( $fakeContext );

		$helper = new SkinLanguageHelper( $skin );
		$languages = $helper->getData();
		$this->assertCount( 1, $languages );
		// T294695: Must be 'Italiano' (standard I), not 'İtaliano' (Turkish İ)
		$this->assertSame( 'Italiano', $languages[0]['text'] );
		$this->assertSame( 'Italiano', $languages[0]['data-language-autonym'] );
	}
}
