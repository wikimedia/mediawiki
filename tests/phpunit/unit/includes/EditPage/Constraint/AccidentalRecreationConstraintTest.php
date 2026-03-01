<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\Title\Title;
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
			false,
			null,
			''
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		// We don't need the full row since the formatting code won't be called if we pass null
		// as $submitButtonLabel
		$row = (object)[
			'actor_name' => '',
			'log_deleted' => false,
			'log_timestamp' => '20260113104445',
		];

		$replicaDatabase = $this->createMock( IReadableDatabase::class );
		$replicaDatabase->method( 'selectRow' )->willReturn( $row );
		$connectionProvider = $this->createMock( IConnectionProvider::class );
		$connectionProvider->method( 'getReplicaDatabase' )->willReturn( $replicaDatabase );

		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( false );
		$title->method( 'hasDeletedEdits' )->willReturn( true );

		$constraint = new AccidentalRecreationConstraint(
			$connectionProvider,
			$this->createMock( LogFormatterFactory::class ),
			$title,
			false,
			// must be less than log_timestamp
			'20260113100000',
			null
		);
		$this->assertConstraintFailed(
			$constraint,
			EditConstraint::AS_ARTICLE_WAS_DELETED
		);
	}

}
