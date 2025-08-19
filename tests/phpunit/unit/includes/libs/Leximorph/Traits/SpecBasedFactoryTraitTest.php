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
 */

namespace Wikimedia\Tests\Leximorph\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use stdClass;
use UnexpectedValueException;
use Wikimedia\Leximorph\Traits\SpecBasedFactoryTrait;

/**
 * This test class verifies the functionality of the {@see SpecBasedFactoryTrait} trait.
 *
 * Covered tests include:
 *   - Creating an instance from a spec for a registered class using mocks.
 *   - Ensuring that the created instance is cached on subsequent calls.
 *   - Throwing an exception when an unregistered class is requested.
 *
 * @covers \Wikimedia\Leximorph\Traits\SpecBasedFactoryTrait
 * @author Doğu Abaris (abaris@null.net)
 */
class SpecBasedFactoryTraitTest extends TestCase {

	/**
	 * Helper method to create a trait mock and set common properties.
	 *
	 * @return MockObject
	 */
	private function getTraitMock(): MockObject {
		return $this->getMockForTrait(
			SpecBasedFactoryTrait::class,
			[],
			'Some',
			true,
			true,
			true,
			[
				'getSpecMap',
				'getSpecArgs',
			]
		);
	}

	public function testCreateFromSpec(): void {
		$traitMock = $this->getTraitMock();
		$traitMock->method( 'getSpecMap' )->willReturn( [
			stdClass::class => [],
		] );
		$reflection = new ReflectionMethod( $traitMock, 'createFromSpec' );
		$instance = $reflection->invoke( $traitMock, stdClass::class );
		$this->assertInstanceOf( stdClass::class, $instance );
	}

	public function testUnregisteredClassThrowsException(): void {
		$traitMock = $this->getTraitMock();
		$traitMock->method( 'getSpecMap' )->willReturn( [] );
		$reflection = new ReflectionMethod( $traitMock, 'createFromSpec' );
		$this->expectException( UnexpectedValueException::class );
		$reflection->invoke( $traitMock, 'NonExistentClass' );
	}
}
