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

namespace Wikimedia\Tests\Leximorph\Handler;

use Generator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Handler\Gender;

/**
 * GenderTest
 *
 * This test class verifies the functionality of the {@see Gender} handler.
 * It tests that the class correctly processes gender-based selection rules
 * based on various input values and expected outcomes.
 *
 * Covered tests include:
 *   - Proper selection of male, female, and other forms.
 *   - Handling of cases where gender value is unexpected.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Handler\Gender
 */
class GenderTest extends TestCase {

	/**
	 * Data provider for testProcess.
	 *
	 * Each test case provides:
	 *  - A gender value.
	 *  - An array of gender-based forms.
	 *  - The expected output.
	 *
	 * @return Generator<array{string, array<string>, string}>
	 */
	public static function provideGenderCases(): Generator {
		yield 'Male' => [ 'male', [ 'he', 'she', 'they' ], 'he' ];
		yield 'Female' => [ 'female', [ 'he', 'she', 'they' ], 'she' ];
		yield 'Other' => [ 'other', [ 'he', 'she', 'they' ], 'they' ];
		yield 'Empty Gender' => [ '', [ 'he', 'she', 'they' ], 'they' ];
		yield 'Undefined Gender' => [ 'nonbinary', [ 'he', 'she', 'they' ], 'they' ];
		yield 'Partial Forms (Male Only)' => [ 'male', [ 'he' ], 'he' ];
		yield 'Partial Forms (Female, No Other)' => [ 'female', [ 'he', 'she' ], 'she' ];
		yield 'Partial Forms (Other Default to Male)' => [ 'other', [ 'he' ], 'he' ];
	}

	/**
	 * @dataProvider provideGenderCases
	 *
	 * Tests that the correct gender form is selected based on the input value.
	 *
	 * @param string $gender Input gender string (e.g., 'male', 'female', 'other').
	 * @param array<string> $forms Array of gendered forms in order: [male, female, other].
	 * @param string $expected Expected result after processing.
	 *
	 * @since 1.45
	 */
	public function testProcess( string $gender, array $forms, string $expected ): void {
		$genderHandler = new Gender();
		$result = $genderHandler->process( $gender, $forms );
		$this->assertSame( $expected, $result );
	}
}
