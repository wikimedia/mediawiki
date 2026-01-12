<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Tests the AccidentalRecreationConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint
 */
class AccidentalRecreationConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new AccidentalRecreationConstraint(
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( CommentStore::class ),
			$this->createMock( IContextSource::class ),
			$this->createMock( Title::class ),
			false,
			null,
			''
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$commentStore = $this->createMock( CommentStore::class );
		$commentStore->method( 'getComment' )->willReturn(
			(object)[
				'text' => ''
			]
		);

		$queryBuilder = $this->createMock( SelectQueryBuilder::class );
		$queryBuilder->method( 'fetchRow' )->willReturn(
			(object)[
				'actor_name' => '',
				'log_deleted' => false,
				'log_timestamp' => '20260113104445',
			],
		);
		$queryBuilder->method( $this->anythingBut( 'fetchRow' ) )->willReturn( $queryBuilder );

		$replicaDatabase = $this->createMock( IReadableDatabase::class );
		$replicaDatabase->method( 'newSelectQueryBuilder' )->willReturn( $queryBuilder );
		$connectionProvider = $this->createMock( IConnectionProvider::class );
		$connectionProvider->method( 'getReplicaDatabase' )->willReturn( $replicaDatabase );

		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( false );
		$title->method( 'hasDeletedEdits' )->willReturn( true );

		$constraint = new AccidentalRecreationConstraint(
			$connectionProvider,
			$commentStore,
			$this->createMock( IContextSource::class ),
			$title,
			false,
			// must be less than log_timestamp
			'20260113100000',
			''
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_ARTICLE_WAS_DELETED
		);
	}

}
