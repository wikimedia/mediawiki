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

use MediaWiki\EditPage\Constraint\ContentModelChangeConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Permissions\PermissionManager;

/**
 * Tests the ContentModelChangeConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ContentModelChangeConstraint
 */
class ContentModelChangeConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->setMethods( [ '__clone', 'setContentModel' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( '__clone' )
			->will( $this->returnSelf() );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $this->equalTo( $newContentModel ) );

		$user = $this->createMock( User::class );

		$permManager = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->setMethods( [ 'userHasRight', 'userCan' ] )
			->getMock();
		$permManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editcontentmodel' )
			)
			->willReturn( true );
		$permManager->expects( $this->exactly( 2 ) )
			->method( 'userCan' )
			->withConsecutive(
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->equalTo( $user ),
					$this->equalTo( $title )
				],
				[
					$this->equalTo( 'edit' ),
					$this->equalTo( $user ),
					$this->equalTo( $title )
				]
			)
			->will(
				$this->onConsecutiveCalls(
					true,
					true
				)
			);

		$constraint = new ContentModelChangeConstraint(
			$permManager,
			$user,
			$title,
			$newContentModel
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$newContentModel = 'FooBarBaz';

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->setMethods( [ '__clone', 'setContentModel' ] )
			->getMock();
		$title->expects( $this->once() )
			->method( '__clone' )
			->will( $this->returnSelf() );
		$title->expects( $this->once() )
			->method( 'setContentModel' )
			->with( $this->equalTo( $newContentModel ) );

		$user = $this->createMock( User::class );

		$permManager = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->setMethods( [ 'userHasRight', 'userCan' ] )
			->getMock();
		$permManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editcontentmodel' )
			)
			->willReturn( true );
		$permManager->expects( $this->exactly( 2 ) )
			->method( 'userCan' )
			->withConsecutive(
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->equalTo( $user ),
					$this->equalTo( $title )
				],
				[
					$this->equalTo( 'edit' ),
					$this->equalTo( $user ),
					$this->equalTo( $title )
				]
			)
			->will(
				$this->onConsecutiveCalls(
					true,
					false // Die at the end
				)
			);

		$constraint = new ContentModelChangeConstraint(
			$permManager,
			$user,
			$title,
			$newContentModel
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);
	}

	public function testFailure_quick() {
		$title = $this->createMock( Title::class );
		$user = $this->createMock( User::class );

		$permManager = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->setMethods( [ 'userHasRight' ] )
			->getMock();
		$permManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editcontentmodel' )
			)
			->willReturn( false );

		$constraint = new ContentModelChangeConstraint(
			$permManager,
			$user,
			$title,
			'FooBarBaz'
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_NO_CHANGE_CONTENT_MODEL
		);
	}

}
