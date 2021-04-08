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

use MediaWiki\EditPage\Constraint\CreationPermissionConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * Tests the CreationPermissionConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\CreationPermissionConstraint
 */
class CreationPermissionConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;
	use MockTitleTrait;

	public function testPass() {
		$title = $this->createMock( Title::class );
		$constraint = new CreationPermissionConstraint(
			$this->mockRegisteredAuthorityWithPermissions( [ 'create' ] ),
			$this->makeMockTitle( __METHOD__ )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$title = $this->createMock( Title::class );

		$constraint = new CreationPermissionConstraint(
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'create' ] ),
			$this->makeMockTitle( __METHOD__ )
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_NO_CREATE_PERMISSION );
	}

}
