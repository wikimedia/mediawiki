<?php

namespace MediaWiki\Tests\Maintenance;

use ManualLogEntry;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Maintenance\FixAutoblockLogTitles;
use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Maintenance\FixAutoblockLogTitles
 * @group Database
 * @author Dreamy Jazz
 */
class FixAutoblockLogTitlesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FixAutoblockLogTitles::class;
	}

	public function testExecuteWhenNoMatchingLogs() {
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Fixing log entries with log_title starting with[\s\S]*done.\n$/" );
	}

	private function getBlockTypeEntry( string $subtype, LinkTarget $target, int $blockId ): int {
		$logEntry = new ManualLogEntry( 'block', $subtype );
		$logEntry->setTarget( $target );
		$logEntry->setPerformer( $this->getTestSysop()->getUser() );
		$logEntry->setRelations( [ 'ipb_id' => $blockId ] );
		return $logEntry->insert();
	}

	public function testExecuteWhenMatchingLogs() {
		// Get three "block" type log entries, where two have the unblock subtype and one of those two has a invalid
		// title.
		$brokenId = $this->getBlockTypeEntry( 'unblock', TitleValue::tryNew( NS_USER, 'User:#1234' ), 1234 );
		$notBrokenId = $this->getBlockTypeEntry( 'unblock', TitleValue::tryNew( NS_USER, '#1236' ), 1236 );
		$notRelevantId = $this->getBlockTypeEntry( 'block', TitleValue::tryNew( NS_USER, 'Abc' ), 1238 );
		// Run the maintenance script and then check that only the $brokenId log entry was updated.
		$this->maintenance->execute();
		$this->expectOutputString(
			"Fixing log entries with log_title starting with 'User:#'\n" .
			"...Processing unblock rows with IDs $brokenId to $notBrokenId\n" .
			"done. Fixed 1 rows.\n"
		);
		$this->newSelectQueryBuilder()
			->select( [ 'log_id', 'log_title' ] )
			->from( 'logging' )
			->orderBy( 'log_id' )
			->assertResultSet( [
				[ $brokenId, '#1234' ],
				[ $notBrokenId, '#1236' ],
				[ $notRelevantId, 'Abc' ],
			] );
	}
}
