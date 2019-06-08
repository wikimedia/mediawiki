<?php

namespace MediaWiki\Tests\Maintenance;

use Exception;
use MediaWiki\MediaWikiServices;
use DumpBackup;
use ManualLogEntry;
use Title;
use User;
use WikiExporter;

/**
 * Tests for log dumps of BackupDumper
 *
 * Some of these tests use the old constuctor for TextPassDumper
 * and the dump() function, while others use the new loadWithArgv( $args )
 * function and execute(). This is to ensure both the old and new methods
 * work properly.
 *
 * @group Database
 * @group Dump
 * @covers BackupDumper
 */
class BackupDumperLoggerTest extends DumpTestCase {

	// We'll add several log entries and users for this test. The following
	// variables hold the corresponding ids.
	private $userId1, $userId2;
	private $logId1, $logId2, $logId3;

	/**
	 * adds a log entry to the database.
	 *
	 * @param string $type Type of the log entry
	 * @param string $subtype Subtype of the log entry
	 * @param User $user User that performs the logged operation
	 * @param int $ns Number of the namespace for the entry's target's title
	 * @param string $title Title of the entry's target
	 * @param string $comment Comment of the log entry
	 * @param array $parameters (optional) accompanying data that is attached to the entry
	 *
	 * @return int Id of the added log entry
	 */
	private function addLogEntry( $type, $subtype, User $user, $ns, $title,
		$comment = null, $parameters = null
	) {
		$logEntry = new ManualLogEntry( $type, $subtype );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( Title::newFromText( $title, $ns ) );
		if ( $comment !== null ) {
			$logEntry->setComment( $comment );
		}
		if ( $parameters !== null ) {
			$logEntry->setParameters( $parameters );
		}

		return $logEntry->insert();
	}

	function addDBData() {
		$this->tablesUsed[] = 'logging';
		$this->tablesUsed[] = 'user';

		try {
			$user1 = User::newFromName( 'BackupDumperLogUserA' );
			$this->userId1 = $user1->getId();
			if ( $this->userId1 === 0 ) {
				$user1->addToDatabase();
				$this->userId1 = $user1->getId();
			}
			$this->assertGreaterThan( 0, $this->userId1 );

			$user2 = User::newFromName( 'BackupDumperLogUserB' );
			$this->userId2 = $user2->getId();
			if ( $this->userId2 === 0 ) {
				$user2->addToDatabase();
				$this->userId2 = $user2->getId();
			}
			$this->assertGreaterThan( 0, $this->userId2 );

			$this->logId1 = $this->addLogEntry( 'type', 'subtype',
				$user1, NS_MAIN, "PageA" );
			$this->assertGreaterThan( 0, $this->logId1 );

			$this->logId2 = $this->addLogEntry( 'supress', 'delete',
				$user2, NS_TALK, "PageB", "SomeComment" );
			$this->assertGreaterThan( 0, $this->logId2 );

			$this->logId3 = $this->addLogEntry( 'move', 'delete',
				$user2, NS_MAIN, "PageA", "SomeOtherComment",
				[ 'key1' => 1, 3 => 'value3' ] );
			$this->assertGreaterThan( 0, $this->logId3 );
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBData in
			// DumpTestCase
			$this->exceptionFromAddDBData = $e;
		}
	}

	function testPlain() {
		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup( [ '--output=file:' . $fname ] );
		$dumper->startId = $this->logId1;
		$dumper->endId = $this->logId3 + 1;
		$dumper->reporting = false;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::LOGS, WikiExporter::TEXT );

		// Analyzing the dumped data
		$this->assertDumpSchema( $fname, $this->getXmlSchemaPath() );

		$asserter = $this->getDumpAsserter();
		$asserter->assertDumpStart( $fname );

		$asserter->assertLogItem( $this->logId1, "BackupDumperLogUserA",
			$this->userId1, null, "type", "subtype", "PageA" );

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$this->assertNotNull( $contLang, "Content language object validation" );
		$namespace = $contLang->getNsText( NS_TALK );
		$this->assertInternalType( 'string', $namespace );
		$this->assertGreaterThan( 0, strlen( $namespace ) );
		$asserter->assertLogItem( $this->logId2, "BackupDumperLogUserB",
			$this->userId2, "SomeComment", "supress", "delete",
			$namespace . ":PageB" );

		$asserter->assertLogItem( $this->logId3, "BackupDumperLogUserB",
			$this->userId2, "SomeOtherComment", "move", "delete",
			"PageA", [ 'key1' => 1, 3 => 'value3' ] );

		$asserter->assertDumpEnd();
	}

	function testXmlDumpsBackupUseCaseLogging() {
		$this->checkHasGzip();

		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup();
		$dumper->loadWithArgv( [ '--logs', '--output=gzip:' . $fname,
			'--reporting=2' ] );
		$dumper->startId = $this->logId1;
		$dumper->endId = $this->logId3 + 1;
		$dumper->setDB( $this->db );

		// xmldumps-backup demands reporting, although this is currently not
		// implemented in BackupDumper, when dumping logging data. We
		// nevertheless capture the output of the dump process already now,
		// to be able to alert (once dumping produces reports) that this test
		// needs updates.
		$dumper->stderr = fopen( 'php://output', 'a' );
		if ( $dumper->stderr === false ) {
			$this->fail( "Could not open stream for stderr" );
		}

		// Performing the dump
		$dumper->execute();

		$this->assertTrue( fclose( $dumper->stderr ), "Closing stderr handle" );

		// Analyzing the dumped data
		$this->gunzip( $fname );

		$this->assertDumpSchema( $fname, $this->getXmlSchemaPath() );

		$asserter = $this->getDumpAsserter();
		$asserter->assertDumpStart( $fname );

		$asserter->assertLogItem( $this->logId1, "BackupDumperLogUserA",
			$this->userId1, null, "type", "subtype", "PageA" );

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$this->assertNotNull( $contLang, "Content language object validation" );
		$namespace = $contLang->getNsText( NS_TALK );
		$this->assertInternalType( 'string', $namespace );
		$this->assertGreaterThan( 0, strlen( $namespace ) );
		$asserter->assertLogItem( $this->logId2, "BackupDumperLogUserB",
			$this->userId2, "SomeComment", "supress", "delete",
			$namespace . ":PageB" );

		$asserter->assertLogItem( $this->logId3, "BackupDumperLogUserB",
			$this->userId2, "SomeOtherComment", "move", "delete",
			"PageA", [ 'key1' => 1, 3 => 'value3' ] );

		$asserter->assertDumpEnd();

		// Currently, no reporting is implemented. Alert via failure, once
		// this changes.
		// If reporting for log dumps has been implemented, please update
		// the following statement to catch good output
		$this->expectOutputString( '' );
	}
}
