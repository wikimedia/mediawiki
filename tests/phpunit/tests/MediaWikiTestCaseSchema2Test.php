<?php

/**
 * @covers MediaWikiTestCase
 *
 * @group Database
 * @group MediaWikiTestCaseTest
 *
 * This test is intended to be executed AFTER MediaWikiTestCaseSchema1Test to ensure
 * that any schema modifications have been cleaned up between test cases.
 */
class MediaWikiTestCaseSchema2Test extends MediaWikiTestCase {

	public function testSchemaExtension() {
		// Make sure MediaWikiTestCaseTestTable created by MediaWikiTestCaseSchema1Test
		// was dropped before executing MediaWikiTestCaseSchema2Test.
		$this->assertFalse( $this->db->tableExists( 'MediaWikiTestCaseTestTable' ) );
	}

	public function testSchemaOverride() {
		// Make sure imagelinks modified by MediaWikiTestCaseSchema1Test
		// was restored to the original schema before executing MediaWikiTestCaseSchema2Test.
		$this->assertFalse( $this->db->fieldExists( 'imagelinks', 'il_frobniz' ) );
	}

}
