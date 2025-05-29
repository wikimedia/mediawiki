<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.42
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
		// Define the configuration to pass to the constructor
		$config = [
			'ipAllowed' => true,
			'ipRangeAllowed' => true,
			'ipRangeLimits' => [ 'min' => 0, 'max' => 32 ],
		];

		// Create the UsersMultiselectWidget object with the above configuration
		/** @var UsersMultiselectWidget $widget */
		$widget = TestingAccessWrapper::newFromObject( new UsersMultiselectWidget( $config ) );

		// Assert that ipAllowed is set correctly
		$this->assertTrue( $widget->ipAllowed, 'ipAllowed was not set correctly.' );

		// Assert that ipRangeAllowed is set correctly
		$this->assertTrue( $widget->ipRangeAllowed, 'ipRangeAllowed was not set correctly.' );

		// Assert that ipRangeLimits is set correctly
		$this->assertSame( $config['ipRangeLimits'], $widget->ipRangeLimits,
			'ipRangeLimits was not set correctly.' );
	}

	public function testGetJavaScriptClassName() {
		// Define the configuration to pass to the constructor
		$config = [
			'ipAllowed' => true,
			'ipRangeAllowed' => true,
			'ipRangeLimits' => [ 'min' => 0, 'max' => 32 ],
		];

		// Create the UsersMultiselectWidget object with the above configuration
		/** @var UsersMultiselectWidget $widget */
		$widget = TestingAccessWrapper::newFromObject( new UsersMultiselectWidget( $config ) );

		// Assert that ipAllowed is set correctly
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
		// Create the UsersMultiselectWidget object with the input configuration
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

		// Assert that the actualConfig matches the expected configuration
		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $actualConfig, "Config missing expected key '$key'" );
			$this->assertEquals( $value, $actualConfig[$key], "Mismatch for key '$key'" );
		}
	}

}
