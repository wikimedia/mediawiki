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

use MediaWiki\EditPage\Constraint\EditRightConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * Tests the EditRightConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditRightConstraint
 */
class EditRightConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;

	public function testPass() {
		$constraint = new EditRightConstraint(
			$this->mockRegisteredAuthorityWithPermissions( [ 'edit' ] )
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param Authority $performer
	 * @param int $expectedValue
	 */
	public function testFailure( Authority $performer, int $expectedValue ) {
		$constraint = new EditRightConstraint( $performer );
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public function provideTestFailure() {
		yield 'Anonymous user' => [
			'performer' => $this->mockAnonAuthorityWithoutPermissions( [ 'edit' ] ),
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_ANON,
		];
		yield 'Registered user' => [
			'performer' => $this->mockRegisteredAuthorityWithoutPermissions( [ 'edit' ] ),
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_LOGGED,
		];
	}

}
