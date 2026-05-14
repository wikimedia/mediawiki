<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\RevisionDeletedConstraint;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;

/**
 * Tests the RevisionDeletedConstraint
 *
 * @covers \MediaWiki\EditPage\Constraint\RevisionDeletedConstraint
 */
class RevisionDeletedConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new RevisionDeletedConstraint(
			false,
			1,
			$this->createMockRevision( false, true ),
			'notnew',
			$this->createMock( Title::class ),
			$this->createMock( Authority::class ),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testPass_newSection() {
		$constraint = new RevisionDeletedConstraint(
			false,
			1,
			$this->createMockRevision( true, false ),
			'new',
			$this->createMock( Title::class ),
			$this->createMock( Authority::class ),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testWarning_revisionDeletedCanSee() {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedURL' )->willReturn( 'TestTitle' );
		$constraint = new RevisionDeletedConstraint(
			false,
			1,
			$this->createMockRevision( true, true ),
			'notnew',
			$title,
			$this->createMock( Authority::class ),
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_REVISION_WAS_DELETED );
	}

	public function testWarning_revisionDeletedCanSeeIgnored() {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedURL' )->willReturn( 'TestTitle' );
		$constraint = new RevisionDeletedConstraint(
			true,
			1,
			$this->createMockRevision( true, true ),
			'notnew',
			$title,
			$this->createMock( Authority::class ),
		);
		$status = $constraint->checkConstraint();
		$this->assertStatusOK( $status );
		$this->assertStatusWarning( 'rev-deleted-text-view', $status );
	}

	public function testFailure_revisionDeleted() {
		$constraint = new RevisionDeletedConstraint(
			false,
			1,
			$this->createMockRevision( true, false ),
			'notnew',
			$this->createMock( Title::class ),
			$this->createMock( Authority::class ),
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_REVISION_WAS_DELETED );
	}

	public function testFailure_revisionMissing() {
		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( true );
		$constraint = new RevisionDeletedConstraint(
			false,
			1,
			null,
			'notnew',
			$title,
			$this->createMock( Authority::class ),
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_REVISION_MISSING );
	}

	private function createMockRevision( bool $isDeleted, bool $canSeeRevision ): RevisionRecord {
		$revRecord = $this->createMock( RevisionStoreRecord::class );
		$revRecord->method( 'userCan' )->willReturn( !$isDeleted || $canSeeRevision );
		$revRecord->method( 'isDeleted' )->willReturn( $isDeleted );
		return $revRecord;
	}

}
