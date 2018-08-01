<?php
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @covers MediaWikiTestCase
 *
 * @group Database
 * @group MediaWikiTestCaseTest
 */
class MediaWikiTestCaseSchema1Test extends MediaWikiTestCase {

	public static $hasRun = false;

	public function setUp() {
		parent::setUp();
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
	}

	public function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'create' => [ 'MediaWikiTestCaseTestTable', 'imagelinks' ],
			'drop' => [ 'oldimage' ],
			'alter' => [ 'pagelinks' ],
			'scripts' => [ __DIR__ . '/MediaWikiTestCaseSchemaTest.sql' ]
		];
	}

	public function testMediaWikiTestCaseSchemaTestOrder() {
		// The test must be run before the second test
		self::$hasRun = true;
		$this->assertTrue( self::$hasRun );
	}

	public function testTableWasCreated() {
		// Make sure MediaWikiTestCaseTestTable was created.
		$this->assertTrue( $this->db->tableExists( 'MediaWikiTestCaseTestTable' ) );
	}

	public function testTableWasDropped() {
		// Make sure oldimage was dropped
		$this->assertFalse( $this->db->tableExists( 'oldimage' ) );
	}

	public function testTableWasOverriden() {
		// Make sure imagelinks was overwritten
		$this->assertTrue( $this->db->tableExists( 'imagelinks' ) );
		$this->assertTrue( $this->db->fieldExists( 'imagelinks', 'il_frobnitz' ) );
	}

	public function testTableWasAltered() {
		// Make sure pagelinks was altered
		$this->assertTrue( $this->db->tableExists( 'pagelinks' ) );
		$this->assertTrue( $this->db->fieldExists( 'pagelinks', 'pl_frobnitz' ) );
	}

}
