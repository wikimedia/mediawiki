<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;

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
			$this->createMock( LogFormatterFactory::class ),
			$this->createMock( Title::class ),
			new PageEditAttemptState(),
			null,
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		// This is a very incomplete mock, just enough not to access any global state on a narrow code path
		$row = (object)[
			'log_deleted' => LogPage::DELETED_USER,
			'log_timestamp' => '20260113104445',
		];

		$replicaDatabase = $this->createMock( IReadableDatabase::class );
		$replicaDatabase->method( 'selectRow' )->willReturn( $row );
		$connectionProvider = $this->createMock( IConnectionProvider::class );
		$connectionProvider->method( 'getReplicaDatabase' )->willReturn( $replicaDatabase );

		$logFormatter = $this->createMock( LogFormatter::class );
		$logFormatter->method( 'getComment' )->willReturn( '' );
		$logFormatterFactory = $this->createMock( LogFormatterFactory::class );
		$logFormatterFactory->method( 'newFromEntry' )->willReturn( $logFormatter );

		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( false );
		$title->method( 'hasDeletedEdits' )->willReturn( true );

		$editAttemptState = new PageEditAttemptState();
		$constraint = new AccidentalRecreationConstraint(
			$connectionProvider,
			$logFormatterFactory,
			$title,
			$editAttemptState,
			// must be less than log_timestamp
			'20260113100000',
		);
		$status = $this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_ARTICLE_WAS_DELETED
		);
		$this->assertTrue( $editAttemptState->shouldAllowRecreation() );
		$this->assertTrue( $status->canResubmit() );
		$this->assertStatusMessage( 'edit-constraint-confirmrecreate-noreason-warning', $status );
	}

}
