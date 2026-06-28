<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Html\Html;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;

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
		$hookContainer = $this->createMock( HookContainer::class );
		$hookMethod = $hookContainer->expects( $this->once() )
			->method( 'run' )
			->with(
				'EditFilterMergedContent',
				$this->anything() // Not worrying about the hook call here
			);
		if ( is_callable( $hookResult ) ) {
			$hookMethod->willReturnCallback( $hookResult );
		} else {
			$hookMethod->willReturn( $hookResult );
		}
		$constraint = new EditFilterMergedContentHookConstraint(
			$hookContainer,
			$this->createMock( UserFactory::class ),
			$this->getMockForAbstractClass( Content::class ),
			$this->createMock( RequestContext::class ),
			'EditSummaryGoesHere',
			true, // Minor edit
			new FakeQqxMessageLocalizer,
			$this->createMock( UserIdentity::class )
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
		// Status has no value set, falls back to AS_HOOK_ERROR_EXPECTED
		$constraint = $this->getConstraint( false );
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_HOOK_ERROR_EXPECTED );
	}

	public function testFailure_badStatus() {
		// Code path 2: Hook returns false, status is bad
		$constraint = $this->getConstraint( static function ( $hookName, $args ) {
			// $args[2] is the status
			$args[2]->setResult( false, 12345 );
			return false;
		} );

		$this->assertConstraintFailed(
			$constraint,
			12345 // Value is set in hook
		);
		$this->assertSame(
			Html::errorBox( "\n(hookaborted)\n" ),
			$constraint->getHookError()
		);
	}

	public function testFailure_notOKStatus() {
		// Code path 3: Hook returns true, but status is not okay
		$constraint = $this->getConstraint( static function ( $hookName, $args ) {
			// $args[2] is the status
			$args[2]->setOK( false );
			return true;
		} );

		$this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_HOOK_ERROR_EXPECTED
		);
		$this->assertSame(
			Html::errorBox( "\n(hookaborted)\n" ),
			$constraint->getHookError()
		);
	}

	public function testFailure_statusMessage() {
		// Code path 4: Hook returns true, but status is bad and has an error
		$constraint = $this->getConstraint( static function ( $hookName, $args ) {
			// $args[2] is the status
			$args[2]->fatal( 'test-hook-error' );
			return true;
		} );

		$this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_HOOK_ERROR_EXPECTED
		);
		$this->assertSame(
			Html::errorBox( "\n(test-hook-error)\n" ),
			$constraint->getHookError()
		);
	}
}
