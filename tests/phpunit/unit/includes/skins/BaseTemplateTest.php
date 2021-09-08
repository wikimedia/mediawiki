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
			[
				[
					'FooterIcons' => [
						'copyright' => [
							'copyright' => '<img src="footer-copyright.png">'
						]
					],
					'RightsIcon' => 'copyright.png',
					'RightsUrl' => 'https://',
					'RightsText' => 'copyright'
				],
				'<img src="footer-copyright.png">',
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
		$hashConfig = new HashConfig( $globals );
		$icon = BaseTemplate::getCopyrightIconHTML( $hashConfig );
		$this->assertEquals( $expected, $icon, $msg );
	}
}
