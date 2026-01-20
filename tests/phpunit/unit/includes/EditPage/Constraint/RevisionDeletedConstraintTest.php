<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\RevisionDeletedConstraint;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;

/**
 * Tests the RevisionDeletedConstraint
 *
 * @covers \MediaWiki\EditPage\Constraint\RevisionDeletedConstraint
 */
class RevisionDeletedConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new RevisionDeletedConstraint(
			$this->createMockArticle( false, true ),
			false,
			1,
			'notnew',
			$this->createMock( Title::class ),
			$this->createMock( User::class ),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testPass_newSection() {
		$constraint = new RevisionDeletedConstraint(
			$this->createMock( Article::class ),
			false,
			1,
			'new',
			$this->createMock( Title::class ),
			$this->createMock( User::class ),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testWarning_revisionDeletedCanSee() {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedURL' )->willReturn( 'TestTitle' );
		$constraint = new RevisionDeletedConstraint(
			$this->createMockArticle( true, true ),
			false,
			1,
			'notnew',
			$title,
			$this->createMock( User::class ),
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_REVISION_WAS_DELETED );
	}

	public function testWarning_revisionDeletedCanSeeIgnored() {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedURL' )->willReturn( 'TestTitle' );
		$constraint = new RevisionDeletedConstraint(
			$this->createMockArticle( true, true ),
			true,
			1,
			'notnew',
			$title,
			$this->createMock( User::class ),
		);
		$status = $constraint->checkConstraint();
		$this->assertStatusOK( $status );
		$this->assertStatusWarning( 'rev-deleted-text-view', $status );
	}

	public function testFailure_revisionDeleted() {
		$constraint = new RevisionDeletedConstraint(
			$this->createMockArticle( true, false ),
			false,
			1,
			'notnew',
			$this->createMock( Title::class ),
			$this->createMock( User::class ),
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_REVISION_WAS_DELETED );
	}

	public function testFailure_revisionMissing() {
		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( true );
		$constraint = new RevisionDeletedConstraint(
			// Article::fetchRevisionRecord will implicitly return null
			$this->createMock( Article::class ),
			false,
			1,
			'notnew',
			$title,
			$this->createMock( User::class ),
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_REVISION_MISSING );
	}

	private function createMockArticle( bool $isDeleted, bool $canSeeRevision ): Article {
		$article = $this->createMock( Article::class );
		$revRecord = $this->createMock( RevisionStoreRecord::class );
		$revRecord->method( 'userCan' )->willReturn( !$isDeleted || $canSeeRevision );
		$revRecord->method( 'isDeleted' )->willReturn( $isDeleted );
		$article->method( 'fetchRevisionRecord' )->willReturn( $revRecord );
		return $article;
	}

}
