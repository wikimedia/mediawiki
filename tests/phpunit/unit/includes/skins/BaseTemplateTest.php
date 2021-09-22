<?php

/**
 * @covers BaseTemplate
 *
 * @license GPL-2.0-or-later
 */
class BaseTemplateTest extends MediaWikiUnitTestCase {
	public function provideGetCopyrightIconHTML() {
		return [
			[
				[
					'RightsIcon' => '',
					'FooterIcons' => [],
				],
				'',
				'Returns empty string when no configuration'
			],
			// When $wgFooterIcons['copyright']['copyright'] returns an array (T291325)
			[
				[
					'FooterIcons' => [
						'copyright' => [
							'copyright' => [
								'src' => 'footer-copyright.png',
								'url' => 'https://t.ui',
								'alt' => 'Alt text',
							]
						]
					],
					'RightsIcon' => 'copyright.png',
					'RightsUrl' => 'https://',
					'RightsText' => 'copyright'
				],
				'<a href="https://t.ui"><img src="footer-copyright.png" alt="Alt text" width="88" height="31" /></a>',
				'Reads from FooterIcons first'
			],
			// When $wgFooterIcons['copyright']['copyright'] returns a string (T291325)
			[
				[
					'FooterIcons' => [
						'copyright' => [
							'copyright' => 'footer-copyright.png'
						]
					]
				],
				'<img src="footer-copyright.png" width="88" height="31" />',
				'Reads from FooterIcons first'
			],
			[
				[
					'FooterIcons' => [],
					'RightsIcon' => 'copyright.png',
					'RightsUrl' => 'https://',
					'RightsText' => 'copyright'
				],
				'<a href="https://"><img src="copyright.png" alt="copyright" width="88" height="31" /></a>',
				'Copyright can be created from Rights- prefixed config variables.'
			],
			[
				[
					'FooterIcons' => [],
					'RightsIcon' => 'copyright.png',
					'RightsUrl' => '',
					'RightsText' => 'copyright'
				],
				'<img src="copyright.png" alt="copyright" width="88" height="31" />',
				'$wgRightsUrl is optional.'
			],
		];
	}

	/**
	 * @covers BaseTemplate::getCopyrightIconHTML
	 * @dataProvider provideGetCopyrightIconHTML
	 */
	public function testGetCopyrightIconHTML( $globals, $expected, $msg ) {
		$mockSkin = $this->createMock( Skin::class );
		$mockSkin->method( 'makeFooterIcon' )->willReturn( $expected );
		$hashConfig = new HashConfig( $globals );
		$icon = BaseTemplate::getCopyrightIconHTML( $hashConfig, $mockSkin );
		$this->assertEquals( $expected, $icon, $msg );
	}
}
