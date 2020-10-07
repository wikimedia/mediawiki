<?php

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @covers MediaWikiIntegrationTestCase
 *
 * @group Database
 * @group MediaWikiIntegrationTestCaseTest
 */
class MediaWikiIntegrationTestCaseSchema1Test extends MediaWikiIntegrationTestCase {

	public static $hasRun = false;

	protected function setUp() : void {
		parent::setUp();
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
	}

	public function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'create' => [ 'MediaWikiIntegrationTestCaseTestTable', 'imagelinks' ],
			'drop' => [ 'oldimage' ],
			'alter' => [ 'pagelinks' ],
			'scripts' => [ __DIR__ . '/MediaWikiIntegrationTestCaseSchemaTest.sql' ]
		];
	}

	public function testMediaWikiIntegrationTestCaseSchemaTestOrder() {
		// The test must be run before the second test
		self::$hasRun = true;
		$this->assertTrue( self::$hasRun );
	}

	public function testTableWasCreated() {
		// Make sure MediaWikiIntegrationTestCaseTestTable was created.
		$this->assertTrue( $this->db->tableExists( 'MediaWikiIntegrationTestCaseTestTable' ) );
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
