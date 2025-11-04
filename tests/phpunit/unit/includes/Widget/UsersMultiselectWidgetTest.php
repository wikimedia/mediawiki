<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Widget;

use MediaWiki\Widget\UsersMultiselectWidget;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Widget\UsersMultiselectWidget
 */
class UsersMultiselectWidgetTest extends MediaWikiUnitTestCase {
	protected bool $ipAllowed;
	protected bool $ipRangeAllowed;
	protected array $ipRangeLimits;

	public function setUp(): void {
		parent::setUp();
		$this->ipAllowed = false;
		$this->ipRangeAllowed = false;
		$this->ipRangeLimits = [];
	}

	public function testConstruct() {
		$config = [
			'ipAllowed' => true,
			'ipRangeAllowed' => true,
			'ipRangeLimits' => [ 'min' => 0, 'max' => 32 ],
		];

		/** @var UsersMultiselectWidget $widget */
		$widget = TestingAccessWrapper::newFromObject( new UsersMultiselectWidget( $config ) );

		$this->assertTrue( $widget->ipAllowed, 'ipAllowed was not set correctly.' );

		$this->assertTrue( $widget->ipRangeAllowed, 'ipRangeAllowed was not set correctly.' );

		$this->assertSame( $config['ipRangeLimits'], $widget->ipRangeLimits,
			'ipRangeLimits was not set correctly.' );
	}

	public function testGetJavaScriptClassName() {
		$config = [
			'ipAllowed' => true,
			'ipRangeAllowed' => true,
			'ipRangeLimits' => [ 'min' => 0, 'max' => 32 ],
		];

		/** @var UsersMultiselectWidget $widget */
		$widget = TestingAccessWrapper::newFromObject( new UsersMultiselectWidget( $config ) );

		$this->assertSame( 'mw.widgets.UsersMultiselectWidget', $widget->getJavaScriptClassName(),
			'getJavaScriptClassName did not return the expected value.' );
	}

	public static function provideGetConfig(): array {
		return [
			'ipAllowed' => [
				[
					'ipAllowed' => true,
				],
				[
					'ipAllowed' => true,
					'ipRangeAllowed' => false,
					'ipRangeLimits' => [
						'min' => 0,
						'max' => 32,
					],
				],
			],
			'ipRangeAllowed' => [
				[
					'ipRangeAllowed' => true,
				],
				[
					'ipAllowed' => false,
					'ipRangeAllowed' => true,
					'ipRangeLimits' => [
						'min' => 0,
						'max' => 32,
					],
				],
			],
			'ipRangeLimits' => [
				[
					'ipRangeLimits' => [ 'min' => 0, 'max' => 64 ],
				],
				[
					'ipAllowed' => false,
					'ipRangeAllowed' => false,
					'ipRangeLimits' => [
						'min' => 0,
						'max' => 64,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetConfig
	 */
	public function testGetConfig( array $inputConfig, array $expected ) {
		/** @var UsersMultiselectWidget $widget */
		$widget = TestingAccessWrapper::newFromObject( new UsersMultiselectWidget( $inputConfig ) );

		$actualConfig = [
			'ipAllowed' => false,
			'ipRangeAllowed' => false,
			'ipRangeLimits' => [
				'min' => 0,
				'max' => 32,
			],
		];

		// TestingAccessWrapper cannot pass-by-ref directly - T287318
		call_user_func_array( [ $widget, 'getConfig' ], [ &$actualConfig ] );

		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $actualConfig, "Config missing expected key '$key'" );
			$this->assertEquals( $value, $actualConfig[$key], "Mismatch for key '$key'" );
		}
	}
}
