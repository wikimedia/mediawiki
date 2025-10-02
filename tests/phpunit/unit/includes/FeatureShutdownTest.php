<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\FeatureShutdown;

/**
 * @covers \MediaWiki\FeatureShutdown
 * @author Taavi Väänänen <hi@taavi.wtf>
 */
class FeatureShutdownTest extends MediaWikiUnitTestCase {
	public function testConstruct() {
		$this->assertInstanceOf(
			FeatureShutdown::class,
			new FeatureShutdown(
				new ServiceOptions(
					FeatureShutdown::CONSTRUCTOR_OPTIONS,
					[ 'FeatureShutdown' => [], ]
				)
			)
		);
	}

	/**
	 * @dataProvider provideNotShutdown
	 */
	public function testNotShutdown( array $shutdowns ) {
		$fs = new FeatureShutdown(
			new ServiceOptions(
				FeatureShutdown::CONSTRUCTOR_OPTIONS,
				[ 'FeatureShutdown' => $shutdowns, ]
			)
		);

		$this->assertNull( $fs->findForFeature( 'legacy-bars' ) );
	}

	public static function provideNotShutdown(): array {
		// reminder: each test case contains a name and an array of parameters,
		// so the only parameter is an array but needs to be wrapped in another array
		return [
			'Empty' => [ [] ],
			'Other feature configured' => [
				[
					'legacy-foos' => [
						[
							'start' => date( 'Y-m-d H:i:s', strtotime( '-1 week' ) ),
							'end' => date( 'Y-m-d H:i:s', strtotime( '+1 week' ) ),
						],
					],
				],
			],
			'Configured but empty' => [
				[
					'legacy-bars' => [],
				],
			],
			'Configured but wrong time' => [
				[
					'legacy-bars' => [
						[
							// Assuming those timestamps are long past
							'start' => '2016-01-01T00:00 +00:00',
							'end' => '2016-02-01T00:00 +00:00',
						],
					],
				],
			],
		];
	}

	public function testShutdown() {
		$shutdown = [
			'start' => date( 'Y-m-d H:i:s', strtotime( '-1 week' ) ),
			'end' => date( 'Y-m-d H:i:s', strtotime( '+1 week' ) ),
		];

		$fs = new FeatureShutdown(
			new ServiceOptions(
				FeatureShutdown::CONSTRUCTOR_OPTIONS,
				[
					'FeatureShutdown' => [
						'legacy-bars' => [ $shutdown ],
					],
				]
			)
		);

		$this->assertEquals( $shutdown, $fs->findForFeature( 'legacy-bars' ) );
	}
}
