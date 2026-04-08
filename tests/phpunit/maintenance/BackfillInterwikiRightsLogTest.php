<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Maintenance;

use BackfillInterwikiRightsLog;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \BackfillInterwikiRightsLog
 * @group Database
 */
class BackfillInterwikiRightsLogTest extends MaintenanceBaseTestCase {

	// Using a name of virtual database, so we can pretend we're connecting to two DBs, even though it's one
	private const REMOTE_WIKI = 'virtual-interwiki';

	protected function getMaintenanceClass() {
		return BackfillInterwikiRightsLog::class;
	}

	public function testDryRunDoesNothing() {
		$this->insertRightsLogs();

		$preLogsCount = $this->countLogs();

		$this->maintenance->setOption( 'remote-wiki', self::REMOTE_WIKI );
		$this->maintenance->setOption( 'dry-run', true );
		$this->maintenance->setArg( 0, '20250101000000' );
		$this->maintenance->execute();

		$postLogsCount = $this->countLogs();

		$this->assertSame( 0, $postLogsCount - $preLogsCount, 'Nothing should be added in dry run mode' );
	}

	public function testCopiesLogs() {
		$this->insertRightsLogs();

		$preLogsCount = $this->countLogs();

		$this->maintenance->setOption( 'remote-wiki', self::REMOTE_WIKI );
		$this->maintenance->setArg( 0, '20250101000000' );
		$this->maintenance->execute();

		$postLogsCount = $this->countLogs();

		$this->assertSame( 1, $postLogsCount - $preLogsCount, 'Only one log entry should be added' );

		$addedRow = DatabaseLogEntry::newSelectQueryBuilder( $this->getDb() )
			->orderBy( 'log_id', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchRow();
		$addedLog = DatabaseLogEntry::newFromRow( $addedRow );

		$this->assertSame( 'rights', $addedLog->getType() );
		$this->assertSame( 'rights', $addedLog->getSubtype() );
		$this->assertSame( 'Target1', $addedLog->getTarget()->getText() );
		$this->assertSame( self::REMOTE_WIKI . '>Performer 1', $addedLog->getPerformerIdentity()->getName() );
		$this->assertSame( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ], $addedLog->getParameters() );
		$this->assertSame( '20200101000000', $addedLog->getTimestamp() );
	}

	public function testSkipsExistingLogs() {
		$this->insertRightsLogs();

		// Copy of $log1 in inserRightsLogs()
		$log1 = new ManualLogEntry( 'rights', 'rights' );
		$log1->setTimestamp( '20200101000000' );
		$log1->setPerformer( new UserIdentityValue( 0, self::REMOTE_WIKI . '>Performer 1' ) );
		$log1->setTarget( new PageIdentityValue( 0, NS_USER, 'Target1', false ) );
		$log1->setParameters( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ] );
		$log1->insert();

		$preLogsCount = $this->countLogs();

		$this->maintenance->setOption( 'remote-wiki', self::REMOTE_WIKI );
		$this->maintenance->setArg( 0, '20250101000000' );
		$this->maintenance->execute();

		$postLogsCount = $this->countLogs();

		$this->assertSame( 0, $postLogsCount - $preLogsCount, 'No log entries should be added' );
	}

	public function testFollowsRenames() {
		$this->insertRightsLogs();

		// Target1 -> Target1b
		$renameLog1 = new ManualLogEntry( 'renameuser', 'renameuser' );
		$renameLog1->setTimestamp( '20200102000000' );
		$renameLog1->setPerformer( new UserIdentityValue( 100, 'Renamer' ) );
		$renameLog1->setTarget( new PageIdentityValue( 0, NS_USER, 'Target1', false ) );
		$renameLog1->setParameters( [ '5::newuser' => 'Target1b' ] );
		$renameLog1->insert();

		// Target1b -> Target1c
		$renameLog2 = new ManualLogEntry( 'renameuser', 'renameuser' );
		$renameLog2->setTimestamp( '20200103000000' );
		$renameLog2->setPerformer( new UserIdentityValue( 100, 'Renamer' ) );
		$renameLog2->setTarget( new PageIdentityValue( 0, NS_USER, 'Target1b', false ) );
		$renameLog2->setParameters( [ '5::newuser' => 'Target1c' ] );
		$renameLog2->insert();

		// Target1 -> TargetX (but happens before original rights log, so should be ignored)
		$renameLog3 = new ManualLogEntry( 'renameuser', 'renameuser' );
		$renameLog3->setTimestamp( '20190101000000' );
		$renameLog3->setPerformer( new UserIdentityValue( 100, 'Renamer' ) );
		$renameLog3->setTarget( new PageIdentityValue( 0, NS_USER, 'Target1', false ) );
		$renameLog3->setParameters( [ '5::newuser' => 'TargetX' ] );
		$renameLog3->insert();

		$preLogsCount = $this->countLogs();

		$this->maintenance->setOption( 'remote-wiki', self::REMOTE_WIKI );
		$this->maintenance->setArg( 0, '20250101000000' );
		$this->maintenance->execute();

		$postLogsCount = $this->countLogs();

		$this->assertSame( 1, $postLogsCount - $preLogsCount, 'Only one log entry should be added' );

		$addedRow = DatabaseLogEntry::newSelectQueryBuilder( $this->getDb() )
			->orderBy( 'log_id', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchRow();
		$addedLog = DatabaseLogEntry::newFromRow( $addedRow );

		$this->assertSame( 'rights', $addedLog->getType() );
		$this->assertSame( 'rights', $addedLog->getSubtype() );
		$this->assertSame( 'Target1c', $addedLog->getTarget()->getText() );
		$this->assertSame( self::REMOTE_WIKI . '>Performer 1', $addedLog->getPerformerIdentity()->getName() );
		$this->assertSame( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ], $addedLog->getParameters() );
		$this->assertSame( '20200101000000', $addedLog->getTimestamp() );
	}

	private function insertRightsLogs() {
		$currWiki = WikiMap::getCurrentWikiId();

		// Interwiki rights log to our wiki - to be copied
		$log1 = new ManualLogEntry( 'rights', 'rights' );
		$log1->setTimestamp( '20200101000000' );
		$log1->setPerformer( new UserIdentityValue( 1, 'Performer 1' ) );
		$log1->setTarget( new PageIdentityValue( 0, NS_USER, 'Target1@' . $currWiki, false ) );
		$log1->setParameters( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ] );
		$log1->insert();

		// Interwiki rights log to other wiki - should be skipped
		$log2 = new ManualLogEntry( 'rights', 'rights' );
		$log2->setTimestamp( '20200101000000' );
		$log2->setPerformer( new UserIdentityValue( 2, 'Performer 2' ) );
		$log2->setTarget( new PageIdentityValue( 0, NS_USER, 'Target2@otherwiki', false ) );
		$log2->setParameters( [] );
		$log2->insert();

		// Not a rights/rights log - should be skipped
		$log3 = new ManualLogEntry( 'rights', 'autopromote' );
		$log3->setTimestamp( '20200101000000' );
		$log3->setPerformer( new UserIdentityValue( 3, 'Performer 3' ) );
		$log3->setTarget( new PageIdentityValue( 0, NS_USER, 'Target3@' . $currWiki, false ) );
		$log3->setParameters( [] );
		$log3->insert();

		// Interwiki rights log to our wiki, but after cutoff date - should be skipped
		$log4 = new ManualLogEntry( 'rights', 'rights' );
		$log4->setTimestamp( '20300101000000' );
		$log4->setPerformer( new UserIdentityValue( 4, 'Performer 4' ) );
		$log4->setTarget( new PageIdentityValue( 0, NS_USER, 'Target4@' . $currWiki, false ) );
		$log4->setParameters( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ] );
		$log4->insert();
	}

	private function countLogs(): int {
		return $this->getDb()
			->newSelectQueryBuilder()
			->select( '*' )
			->from( 'logging' )
			->caller( __METHOD__ )
			->fetchRowCount();
	}

	public function testBreaksRenameCycles() {
		// Interwiki rights log to our wiki - to be copied
		// Don't use insertRightsLogs(), as here the target is different, to specifically test usernames with spaces
		$currWiki = WikiMap::getCurrentWikiId();
		$log1 = new ManualLogEntry( 'rights', 'rights' );
		$log1->setTimestamp( '20200101000000' );
		$log1->setPerformer( new UserIdentityValue( 1, 'Performer 1' ) );
		$log1->setTarget( new PageIdentityValue( 0, NS_USER, 'Target_1@' . $currWiki, false ) );
		$log1->setParameters( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ] );
		$log1->insert();

		// Target 1 -> Target 2
		$renameLog1 = new ManualLogEntry( 'renameuser', 'renameuser' );
		$renameLog1->setTimestamp( '20200102000000' );
		$renameLog1->setPerformer( new UserIdentityValue( 100, 'Renamer' ) );
		$renameLog1->setTarget( new PageIdentityValue( 0, NS_USER, 'Target_1', false ) );
		$renameLog1->setParameters( [ '5::newuser' => 'Target 2' ] );
		$renameLog1->insert();

		// Target 2 -> Target 1
		$renameLog2 = new ManualLogEntry( 'renameuser', 'renameuser' );
		$renameLog2->setTimestamp( '20200103000000' );
		$renameLog2->setPerformer( new UserIdentityValue( 100, 'Renamer' ) );
		$renameLog2->setTarget( new PageIdentityValue( 0, NS_USER, 'Target_2', false ) );
		$renameLog2->setParameters( [ '5::newuser' => 'Target 1' ] );
		$renameLog2->insert();

		$preLogsCount = $this->countLogs();

		$this->maintenance->setOption( 'remote-wiki', self::REMOTE_WIKI );
		$this->maintenance->setArg( 0, '20250101000000' );
		$this->maintenance->execute();

		$postLogsCount = $this->countLogs();

		$this->assertSame( 1, $postLogsCount - $preLogsCount, 'Only one log entry should be added' );

		$addedRow = DatabaseLogEntry::newSelectQueryBuilder( $this->getDb() )
			->orderBy( 'log_id', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchRow();
		$addedLog = DatabaseLogEntry::newFromRow( $addedRow );

		$this->assertSame( 'rights', $addedLog->getType() );
		$this->assertSame( 'rights', $addedLog->getSubtype() );
		$this->assertSame( 'Target 1', $addedLog->getTarget()->getText() );
		$this->assertSame( self::REMOTE_WIKI . '>Performer 1', $addedLog->getPerformerIdentity()->getName() );
		$this->assertSame( [ '4::oldgroups' => [], '5::newgroups' => [ 'sysop' ] ], $addedLog->getParameters() );
		$this->assertSame( '20200101000000', $addedLog->getTimestamp() );
	}
}
