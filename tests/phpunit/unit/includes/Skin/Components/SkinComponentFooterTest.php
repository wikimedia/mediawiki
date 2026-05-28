<?php
use MediaWiki\Config\HashConfig;
use MediaWiki\Skin\Components\SkinComponentFooter;
use MediaWiki\Skin\Components\SkinComponentRegistryContext;

/**
 * @covers \MediaWiki\Skin\Components\SkinComponentFooter
 * @group Skin
 */
class SkinComponentFooterTest extends MediaWikiUnitTestCase {
	public static function provideMakeFooterIconHTML() {
		$config = new HashConfig( [] );
		return [
			[
				$config,
				'<img src="foo.gif">',
				'withImage',
				'<img src="foo.gif">'
			],
			[
				$config,
				[
					'src' => 'foo.jpg',
				],
				'withImage',
				'<img src="foo.jpg" loading="lazy">'
			],
			[
				$config,
				[
					'src' => 'foo.jpg',
					'sources' => [
						[
							'media' => '(min-width: 500px)',
							'srcset' => 'fooXL.jpg',
						],
					]
				],
				'withImage',
				[
					'<img src="foo.jpg" loading="lazy">',
					'<picture',
					'<source media="(min-width: 500px)" srcset="fooXL.jpg',
				],
			],
			[
				$config,
				[
					'src' => 'foo.jpg',
					'alt' => 'alt text'
				],
				false,
				'alt text',
			]
		];
	}

	/**
	 * @dataProvider provideMakeFooterIconHTML
	 */
	public function testMakeFooterIconHTML( $config, $icon, $withImage, $result ) {
		$ctx = $this->createMock( SkinComponentRegistryContext::class );
		$footer = new SkinComponentFooter( $ctx );
		$html = $footer::makeFooterIconHTML( $config, $icon, $withImage );
		if ( is_array( $result ) ) {
			foreach ( $result as $str ) {
				$this->assertStringContainsString( $str, $html );
			}
		} else {
			$this->assertStringContainsString( $result, $html );
		}
	}

	/**
	 * Verify that addItem() stores extra items and invalidates the
	 * cached template data so they are included in subsequent
	 * getTemplateData() calls (T426358).
	 *
	 * @covers \MediaWiki\Skin\Components\SkinComponentFooter::addItem
	 */
	public function testAddItemStoresItemsAndInvalidatesCache() {
		$ctx = $this->createMock( SkinComponentRegistryContext::class );
		$footer = new SkinComponentFooter( $ctx );
		$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $footer );

		// Simulate a cached state
		$wrapper->cachedTemplateData = [ 'stale' => true ];

		$footer->addItem( 'places', [
			'ext-link' => [
				'id' => 'footer-places-ext-link',
				'html' => '<a href="#">Extension Footer</a>',
			],
		] );

		$this->assertNull( $wrapper->cachedTemplateData,
			'addItem() must invalidate the template data cache' );
		$this->assertArrayHasKey( 'places', $wrapper->extraItems,
			'addItem() must store items under the given section' );
		$this->assertArrayHasKey( 'ext-link', $wrapper->extraItems['places'],
			'addItem() must store items keyed by item name' );
	}
}
