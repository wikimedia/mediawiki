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

use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\HookContainer\HookContainer;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests the EditFilterMergedContentHookConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint
 */
class EditFilterMergedContentHookConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getConstraint( $hookResult ) {
		$context = new RequestContext();
		$user = $this->createMock( User::class );
		$language = $this->createMock( Language::class );
		$context->setUser( $user );
		$context->setLanguage( $language );

		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->expects( $this->once() )
			->method( 'run' )
			->with(
				$this->equalTo( 'EditFilterMergedContent' ),
				$this->anything() // Not worrying about the hook call here
			)
			->willReturn( $hookResult );
		$constraint = new EditFilterMergedContentHookConstraint(
			$hookContainer,
			$this->getMockForAbstractClass( Content::class ),
			$context,
			'EditSummaryGoesHere',
			true // Minor edit
		);
		return $constraint;
	}

	public function testPass() {
		$constraint = $this->getConstraint( true );
		$this->assertConstraintPassed( $constraint );
		$this->assertSame( '', $constraint->getHookError() );
	}

	public function testFailure_goodStatus() {
		// Code path 1: Hook returns false, but status is still good
		// Status has no value set, falls back to AS_HOOK_ERROR
		$constraint = $this->getConstraint( false );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_HOOK_ERROR );
	}

	public function testFailure_badStatus() {
		// Code path 2: Hook returns false, status is bad
		// To avoid using the real Status::getWikiText, which can use global state, etc.,
		// replace the status object with a mock
		$constraint = $this->getConstraint( false );
		$mockStatus = $this->getMockBuilder( Status::class )
			->setMethods( [ 'isGood', 'getWikiText' ] )
			->getMock();
		$mockStatus->method( 'isGood' )->willReturn( false );
		$mockStatus->method( 'getWikiText' )->willReturn( 'WIKITEXT' );
		$mockStatus->value = 12345;
		TestingAccessWrapper::newFromObject( $constraint )->status = $mockStatus;

		$this->assertConstraintFailed(
			$constraint,
			12345 // Value is set in hook (or in this case in the mock)
		);
	}

	public function testFailure_notOKStatus() {
		// Code path 3: Hook returns true, but status is not okay
		// To avoid using the real Status::getWikiText, which can use global state, etc.,
		// replace the status object with a mock
		$constraint = $this->getConstraint( true );
		$mockStatus = $this->getMockBuilder( Status::class )
			->setMethods( [ 'isOK', 'getErrors', 'getWikiText' ] )
			->getMock();
		$mockStatus->method( 'isOK' )->willReturn( false );
		$mockStatus->method( 'getErrors' )->willReturn( [] );
		$mockStatus->method( 'getWikiText' )->willReturn( 'WIKITEXT' );
		TestingAccessWrapper::newFromObject( $constraint )->status = $mockStatus;

		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_HOOK_ERROR_EXPECTED
		);
	}

}
