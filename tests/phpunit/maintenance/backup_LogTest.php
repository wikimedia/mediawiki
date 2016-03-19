<?php
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

	/**
	 * asserts that the xml reader is at the beginning of a log entry and skips over
	 * it while analyzing it.
	 *
	 * @param int $id Id of the log entry
	 * @param string $user_name User name of the log entry's performer
	 * @param int $user_id User id of the log entry 's performer
	 * @param string|null $comment Comment of the log entry. If null, the comment text is ignored.
	 * @param string $type Type of the log entry
	 * @param string $subtype Subtype of the log entry
	 * @param string $title Title of the log entry's target
	 * @param array $parameters (optional) unserialized data accompanying the log entry
	 */
	private function assertLogItem( $id, $user_name, $user_id, $comment, $type,
		$subtype, $title, $parameters = []
	) {

		$this->assertNodeStart( "logitem" );
		$this->skipWhitespace();

		$this->assertTextNode( "id", $id );
		$this->assertTextNode( "timestamp", false );

		$this->assertNodeStart( "contributor" );
		$this->skipWhitespace();
		$this->assertTextNode( "username", $user_name );
		$this->assertTextNode( "id", $user_id );
		$this->assertNodeEnd( "contributor" );
		$this->skipWhitespace();

		if ( $comment !== null ) {
			$this->assertTextNode( "comment", $comment );
		}
		$this->assertTextNode( "type", $type );
		$this->assertTextNode( "action", $subtype );
		$this->assertTextNode( "logtitle", $title );

		$this->assertNodeStart( "params" );
		$parameters_xml = unserialize( $this->xml->value );
		$this->assertEquals( $parameters, $parameters_xml );
		$this->assertTrue( $this->xml->read(), "Skipping past processed text of params" );
		$this->assertNodeEnd( "params" );
		$this->skipWhitespace();

		$this->assertNodeEnd( "logitem" );
		$this->skipWhitespace();
	}

	function testPlain() {
		global $wgContLang;

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
		$this->assertDumpStart( $fname );

		$this->assertLogItem( $this->logId1, "BackupDumperLogUserA",
			$this->userId1, null, "type", "subtype", "PageA" );

		$this->assertNotNull( $wgContLang, "Content language object validation" );
		$namespace = $wgContLang->getNsText( NS_TALK );
		$this->assertInternalType( 'string', $namespace );
		$this->assertGreaterThan( 0, strlen( $namespace ) );
		$this->assertLogItem( $this->logId2, "BackupDumperLogUserB",
			$this->userId2, "SomeComment", "supress", "delete",
			$namespace . ":PageB" );

		$this->assertLogItem( $this->logId3, "BackupDumperLogUserB",
			$this->userId2, "SomeOtherComment", "move", "delete",
			"PageA", [ 'key1' => 1, 3 => 'value3' ] );

		$this->assertDumpEnd();
	}

	function testXmlDumpsBackupUseCaseLogging() {
		global $wgContLang;

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

		$this->assertDumpStart( $fname );

		$this->assertLogItem( $this->logId1, "BackupDumperLogUserA",
			$this->userId1, null, "type", "subtype", "PageA" );

		$this->assertNotNull( $wgContLang, "Content language object validation" );
		$namespace = $wgContLang->getNsText( NS_TALK );
		$this->assertInternalType( 'string', $namespace );
		$this->assertGreaterThan( 0, strlen( $namespace ) );
		$this->assertLogItem( $this->logId2, "BackupDumperLogUserB",
			$this->userId2, "SomeComment", "supress", "delete",
			$namespace . ":PageB" );

		$this->assertLogItem( $this->logId3, "BackupDumperLogUserB",
			$this->userId2, "SomeOtherComment", "move", "delete",
			"PageA", [ 'key1' => 1, 3 => 'value3' ] );

		$this->assertDumpEnd();

		// Currently, no reporting is implemented. Alert via failure, once
		// this changes.
		// If reporting for log dumps has been implemented, please update
		// the following statement to catch good output
		$this->expectOutputString( '' );
	}
}
