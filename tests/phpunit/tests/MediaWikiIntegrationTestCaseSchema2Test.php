<?php

/**
 * @covers MediaWikiIntegrationTestCase
 *
 * @group Database
 * @group MediaWikiIntegrationTestCaseTest
 *
 * This test is intended to be executed AFTER MediaWikiIntegrationTestCaseSchema1Test to ensure
 * that any schema modifications have been cleaned up between test cases.
 * As there seems to be no way to force execution order, we currently rely on
 * test classes getting run in anpha-numerical order.
 * Order is checked by the testMediaWikiIntegrationTestCaseSchemaTestOrder test in both classes.
 */
class MediaWikiIntegrationTestCaseSchema2Test extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
	}

	public function testMediaWikiIntegrationTestCaseSchemaTestOrder() {
		// The first test must have run before this one
		$this->assertTrue( MediaWikiIntegrationTestCaseSchema1Test::$hasRun );
	}

	public function testCreatedTableWasRemoved() {
		// Make sure MediaWikiIntegrationTestCaseTestTable created by MediaWikiIntegrationTestCaseSchema1Test
		// was dropped before executing MediaWikiIntegrationTestCaseSchema2Test.
		$this->assertFalse( $this->db->tableExists( 'MediaWikiIntegrationTestCaseTestTable' ) );
	}

	public function testDroppedTableWasRestored() {
		// Make sure oldimage that was dropped by MediaWikiIntegrationTestCaseSchema1Test
		// was restored before executing MediaWikiIntegrationTestCaseSchema2Test.
		$this->assertTrue( $this->db->tableExists( 'oldimage' ) );
	}

	public function testOverridenTableWasRestored() {
		// Make sure imagelinks overwritten by MediaWikiIntegrationTestCaseSchema1Test
		// was restored to the original schema before executing MediaWikiIntegrationTestCaseSchema2Test.
		$this->assertTrue( $this->db->tableExists( 'imagelinks' ) );
		$this->assertFalse( $this->db->fieldExists( 'imagelinks', 'il_frobnitz' ) );
	}

	public function testAlteredTableWasRestored() {
		// Make sure pagelinks altered by MediaWikiIntegrationTestCaseSchema1Test
		// was restored to the original schema before executing MediaWikiIntegrationTestCaseSchema2Test.
		$this->assertTrue( $this->db->tableExists( 'pagelinks' ) );
		$this->assertFalse( $this->db->fieldExists( 'pagelinks', 'pl_frobnitz' ) );
	}

}
