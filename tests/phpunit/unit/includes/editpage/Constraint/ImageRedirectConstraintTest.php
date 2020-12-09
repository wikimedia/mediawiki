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

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\ImageRedirectConstraint;
use MediaWiki\Permissions\PermissionManager;

/**
 * Tests the ImageRedirectConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ImageRedirectConstraint
 */
class ImageRedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	/**
	 * @param bool $userIsAnon
	 * @param bool $userHasRight
	 * @return ImageRedirectConstraint
	 */
	private function getConstraint( bool $userIsAnon, bool $userHasRight ) {
		$content = $this->createMock( Content::class );
		$content->method( 'isRedirect' )->willReturn( true );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_FILE );

		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $userIsAnon );

		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'upload' )
			)
			->willReturn( $userHasRight );

		return new ImageRedirectConstraint(
			$permissionManager,
			$content,
			$title,
			$user
		);
	}

	public function testPass() {
		$constraint = $this->getConstraint(
			true, // is anon, does not matter
			true // has `upload` right
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param bool $isAnon
	 * @param int $expectedValue
	 */
	public function testFailure( $isAnon, $expectedValue ) {
		$constraint = $this->getConstraint(
			$isAnon,
			false // lacks `upload` right
		);
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public function provideTestFailure() {
		yield 'Anonymous user' => [ true, IEditConstraint::AS_IMAGE_REDIRECT_ANON ];
		yield 'Registered user' => [ false, IEditConstraint::AS_IMAGE_REDIRECT_LOGGED ];
	}

}
