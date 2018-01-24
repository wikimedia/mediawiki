<?php

/**
 * @covers MediaWikiTestCase
 *
 * @group Database
 * @group MediaWikiTestCaseTest
 *
 * This test is intended to be executed AFTER MediaWikiTestCaseSchema1Test to ensure
 * that any schema modifications have been cleaned up between test cases.
 * As there seems to be no way to force execution order, we currently rely on
 * test classes getting run in anpha-numerical order.
 * Order is checked by the testMediaWikiTestCaseSchemaTestOrder test in both classes.
 */
class MediaWikiTestCaseSchema2Test extends MediaWikiTestCase {

	public function testMediaWikiTestCaseSchemaTestOrder() {
		// The first test must have run before this one
		$this->assertTrue( MediaWikiTestCaseSchema1Test::$hasRun );
	}

	public function testSchemaExtension() {
		// Make sure MediaWikiTestCaseTestTable created by MediaWikiTestCaseSchema1Test
		// was dropped before executing MediaWikiTestCaseSchema2Test.
		$this->assertFalse( $this->db->tableExists( 'MediaWikiTestCaseTestTable' ) );
	}

	public function testSchemaOverride() {
		// Make sure imagelinks modified by MediaWikiTestCaseSchema1Test
		// was restored to the original schema before executing MediaWikiTestCaseSchema2Test.
		$this->assertTrue( $this->db->tableExists( 'imagelinks' ) );
		$this->assertFalse( $this->db->fieldExists( 'imagelinks', 'il_frobniz' ) );
	}

}
