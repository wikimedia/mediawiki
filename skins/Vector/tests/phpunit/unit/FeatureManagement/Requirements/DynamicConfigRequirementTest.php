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
 * @since 1.35
 */

namespace MediaWiki\Skins\Vector\Tests\Unit\FeatureManagement\Requirements;

use MediaWiki\Skins\Vector\FeatureManagement\Requirements\DynamicConfigRequirement;

/**
 * @group Vector
 * @group FeatureManagement
 * @coversDefaultClass \MediaWiki\Skins\Vector\FeatureManagement\Requirements\DynamicConfigRequirement
 */
class DynamicConfigRequirementTest extends \MediaWikiUnitTestCase {

	public static function providesBooleanStates(): array {
		return [ [ false ], [ true ] ];
	}

	/**
	 * @dataProvider providesBooleanStates
	 * @covers ::isMet
	 */
	public function testItFetchesAndReturnsConfigValue( bool $configValue ) {
		$config = new \HashConfig( [
			'Foo' => $configValue,
		] );

		$requirement = new DynamicConfigRequirement( $config, 'Foo', 'Bar' );

		$this->assertEquals( $requirement->isMet(), $configValue );
	}

	/**
	 * @covers ::isMet
	 */
	public function testItCastsConfigValue() {
		$config = new \HashConfig( [
			'Foo' => new \stdClass(),
		] );

		$requirement = new DynamicConfigRequirement( $config, 'Foo', 'Bar' );

		$this->assertTrue( $requirement->isMet() );
	}

	/**
	 * @covers ::getName
	 */
	public function testItReturnsName() {
		$requirement = new DynamicConfigRequirement(
			new \HashConfig(),
			'Foo',
			'Bar'
		);

		$this->assertEquals( 'Bar', $requirement->getName() );
	}
}
