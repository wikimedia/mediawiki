<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialMyLanguage;
use MediaWiki\Specials\SpecialPageLanguage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialMyLanguage
 */
class SpecialMyLanguageTest extends MediaWikiIntegrationTestCase {
	public function addDBDataOnce() {
		$titles = [
			'Page/Another',
			'Page/Another/ar',
			'Page/Another/en',
			'Page/Another/ru',
			'Page/Another/zh',
			'Page/Foreign',
			'Page/Foreign/en',
			'Page/Foreign/zh',
			'Page/Redirect',
		];
		// In the real-world, they are in respective languages,
		// but we don't need to set all of them for tests.
		$pageLang = [
			'Page/Foreign' => 'zh',
		];
		$pageContent = [
			'Page/Redirect' => '#REDIRECT [[Page/Another#Section]]',
		];
		$user = $this->getTestSysop()->getUser();
		$context = RequestContext::getMain();
		$context->setUser( $user );
		foreach ( $titles as $title ) {
			$this->editPage(
				$title,
				new WikitextContent( $pageContent[$title] ?? 'SpecialMyLanguageTest content' ),
				'SpecialMyLanguageTest Summary',
				NS_MAIN,
				$user
			);
			if ( isset( $pageLang[$title] ) ) {
				SpecialPageLanguage::changePageLanguage(
					$context, Title::newFromText( $title ), $pageLang[$title], 'Test' );
			}
		}
	}

	/**
	 * @covers \MediaWiki\Specials\SpecialMyLanguage::findTitle
	 * @dataProvider provideFindTitle
	 * @param string $expected
	 * @param string $subpage
	 * @param string $contLang
	 * @param string $userLang
	 */
	public function testFindTitle( $expected, $subpage, $contLang, $userLang ) {
		$this->setContentLang( $contLang );
		$services = $this->getServiceContainer();
		$special = new SpecialMyLanguage(
			$services->getLanguageNameUtils(),
			$services->getRedirectLookup()
		);
		$special->getContext()->setLanguage( $userLang );
		$this->overrideConfigValue( MainConfigNames::PageLanguageUseDB, true );
		// Test with subpages both enabled and disabled
		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => true ] );
		$this->assertTitle( $expected, $special->findTitle( $subpage ) );
		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => false ] );
		$this->assertTitle( $expected, $special->findTitle( $subpage ) );
	}

	/**
	 * @param string $expected
	 * @param Title|null $title
	 */
	private function assertTitle( $expected, $title ) {
		if ( $expected === null ) {
			$this->assertNull( $title );
		} else {
			$expected = Title::newFromText( $expected );
			$this->assertTrue( $expected->isSameLinkAs( $title ) );
		}
	}

	public static function provideFindTitle() {
		// See addDBDataOnce() for page declarations
		return [
			// [ $expected, $subpage, $contLang, $userLang ]
			[ null, '::Fail', 'en', 'en' ],
			[ 'Page/Another', 'Page/Another/en', 'en', 'en' ],
			[ 'Page/Another', 'Page/Another', 'en', 'en' ],
			[ 'Page/Another/ru', 'Page/Another', 'en', 'ru' ],
			[ 'Page/Another', 'Page/Another', 'en', 'es' ],
			[ 'Page/Another/zh', 'Page/Another', 'en', 'zh' ],
			[ 'Page/Another/zh', 'Page/Another', 'en', 'zh-hans' ],
			[ 'Page/Another/zh', 'Page/Another', 'en', 'zh-mo' ],
			[ 'Page/Another/zh', 'Page/Another', 'en', 'gan' ],
			[ 'Page/Another/zh', 'Page/Another', 'en', 'gan-hant' ],
			[ 'Page/Another/en', 'Page/Another', 'de', 'es' ],
			[ 'Page/Another/ar', 'Page/Another', 'en', 'ar' ],
			[ 'Page/Another/ar', 'Page/Another', 'en', 'arz' ],
			[ 'Page/Another/ar', 'Page/Another/de', 'en', 'arz' ],
			[ 'Page/Another/ru', 'Page/Another/ru', 'en', 'arz' ],
			[ 'Page/Another/ar', 'Page/Another/ru', 'en', 'ar' ],
			[ null, 'Special:Blankpage', 'en', 'ar' ],
			[ null, 'Media:Fail', 'en', 'ar' ],
			[ 'Page/Foreign/en', 'Page/Foreign', 'en', 'en' ],
			[ 'Page/Foreign', 'Page/Foreign', 'en', 'zh-hk' ],
			[ 'Page/Another/ar#Section', 'Page/Redirect', 'en', 'ar' ],
		];
	}

	/**
	 * @covers \MediaWiki\Specials\SpecialMyLanguage::findTitleForTransclusion
	 * @dataProvider provideFindTitleForTransclusion
	 * @param string $expected
	 * @param string $subpage
	 * @param string $langCode
	 * @param string $userLang
	 */
	public function testFindTitleForTransclusion( $expected, $subpage, $langCode, $userLang ) {
		$this->setContentLang( $langCode );
		$services = $this->getServiceContainer();
		$special = new SpecialMyLanguage(
			$services->getLanguageNameUtils(),
			$services->getRedirectLookup()
		);
		$special->getContext()->setLanguage( $userLang );
		// Test with subpages both enabled and disabled
		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => true ] );
		$this->assertTitle( $expected, $special->findTitleForTransclusion( $subpage ) );
		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => false ] );
		$this->assertTitle( $expected, $special->findTitleForTransclusion( $subpage ) );
	}

	public static function provideFindTitleForTransclusion() {
		// See addDBDataOnce() for page declarations
		return [
			// [ $expected, $subpage, $langCode, $userLang ]
			[ 'Page/Another/en', 'Page/Another/en', 'en', 'en' ],
			[ 'Page/Another/en', 'Page/Another', 'en', 'en' ],
			[ 'Page/Another/en', 'Page/Another', 'en', 'frc' ],
		];
	}
}
