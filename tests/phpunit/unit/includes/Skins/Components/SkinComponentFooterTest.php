<?php
use MediaWiki\Config\HashConfig;
use MediaWiki\Skin\SkinComponentFooter;
use MediaWiki\Skin\SkinComponentRegistryContext;

/**
 * @covers \MediaWiki\Skin\SkinComponentFooter
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
}
