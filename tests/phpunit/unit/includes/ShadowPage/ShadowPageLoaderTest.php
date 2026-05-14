<?php

namespace MediaWiki\Tests\ShadowPage;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\ShadowPage\BaseShadowPage;
use MediaWiki\ShadowPage\BaseShadowPageProvider;
use MediaWiki\ShadowPage\ShadowPage;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Title\TitleValue;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\ServiceContainer;

/**
 * @covers \MediaWiki\ShadowPage\ShadowPageLoader
 */
class ShadowPageLoaderTest extends \MediaWikiUnitTestCase {
	private PageReferenceValue $shadowed;
	private PageReferenceValue $unshadowed;

	public function setUp(): void {
	}

	private function newLoader() {
		$singlePageCallback = static function ( LinkTarget|PageReference $title ) {
			if ( $title->getNamespace() === NS_TALK && $title->getDBkey() === 'Shadowed' ) {
				return new BaseShadowPage();
			} else {
				return null;
			}
		};
		$namespaceCallback = function ( LinkTarget|PageReference $title ) {
			$this->assertSame( NS_USER, $title->getNamespace() );
			return new BaseShadowPage();
		};
		$singlePageProvider = $this->createMock( BaseShadowPageProvider::class );
		$singlePageProvider->method( 'get' )->willReturnCallback( $singlePageCallback );
		$singlePageProvider->method( 'existsForLink' )->willReturnCallback(
			static fn ( $title ) => (bool)$singlePageCallback( $title )
		);
		$namespaceProvider = $this->createMock( BaseShadowPageProvider::class );
		$namespaceProvider->method( 'get' )->willReturnCallback( $namespaceCallback );
		$namespaceProvider->method( 'existsForLink' )->willReturnCallback(
			static fn ( $title ) => (bool)$namespaceCallback( $title )
		);

		return new ShadowPageLoader(
			new ObjectFactory( new ServiceContainer() ),
			[
				[ 'factory' => static fn () => $singlePageProvider ],
			],
			[
				[
					'factory' => static fn () => $namespaceProvider,
					'namespace' => NS_USER
				],
			]
		);
	}

	public function testGetMissing() {
		$loader = $this->newLoader();
		$unshadowed = new PageReferenceValue(
			NS_TALK, 'Unshadowed', WikiAwareEntity::LOCAL );
		$page = $loader->get( $unshadowed );
		$this->assertNull( $page );
	}

	public function testGetCached() {
		$shadowed = new PageReferenceValue(
			NS_TALK, 'Shadowed', WikiAwareEntity::LOCAL );
		$loader = $this->newLoader();
		$page1 = $loader->get( $shadowed );
		$this->assertNotNull( $page1 );
		$page2 = $loader->get( $shadowed );
		$this->assertSame( $page1, $page2 );
	}

	public function testGetFromNamespace() {
		$loader = $this->newLoader();
		$title = new PageReferenceValue( NS_USER, 'Foo', WikiAwareEntity::LOCAL );
		$page = $loader->get( $title );
		$this->assertInstanceOf( ShadowPage::class, $page );
	}

	public static function provideExistsForLink() {
		return [
			[ NS_TALK, 'Shadowed', true ],
			[ NS_TALK, 'Unshadowed', false ],
			[ NS_USER, 'Foo', true ],
			[ NS_MAIN, 'Foo', false ],
		];
	}

	/**
	 * @dataProvider provideExistsForLink
	 * @param int $ns
	 * @param string $dbKey
	 * @param bool $expected
	 */
	public function testExistsForLink( $ns, $dbKey, $expected ) {
		$loader = $this->newLoader();
		$title = new TitleValue( $ns, $dbKey );
		$result = $loader->existsForLink( $title );
		$this->assertSame( $expected, $result );
	}
}
