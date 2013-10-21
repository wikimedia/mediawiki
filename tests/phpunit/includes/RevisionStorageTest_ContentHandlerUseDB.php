<?php

/**
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 */
class RevisionTest_ContentHandlerUseDB extends RevisionStorageTest {

	protected function setUp() {
		$this->setMwGlobals( 'wgContentHandlerUseDB', false );

		$dbw = wfGetDB( DB_MASTER );

		$page_table = $dbw->tableName( 'page' );
		$revision_table = $dbw->tableName( 'revision' );
		$archive_table = $dbw->tableName( 'archive' );

		if ( $dbw->fieldExists( $page_table, 'page_content_model' ) ) {
			$dbw->query( "alter table $page_table drop column page_content_model" );
			$dbw->query( "alter table $revision_table drop column rev_content_model" );
			$dbw->query( "alter table $revision_table drop column rev_content_format" );
			$dbw->query( "alter table $archive_table drop column ar_content_model" );
			$dbw->query( "alter table $archive_table drop column ar_content_format" );
		}

		parent::setUp();
	}

	/**
	 * @covers Revision::selectFields
	 */
	public function testSelectFields() {
		$fields = Revision::selectFields();

		$this->assertTrue( in_array( 'rev_id', $fields ), 'missing rev_id in list of fields' );
		$this->assertTrue( in_array( 'rev_page', $fields ), 'missing rev_page in list of fields' );
		$this->assertTrue( in_array( 'rev_timestamp', $fields ), 'missing rev_timestamp in list of fields' );
		$this->assertTrue( in_array( 'rev_user', $fields ), 'missing rev_user in list of fields' );

		$this->assertFalse( in_array( 'rev_content_model', $fields ), 'missing rev_content_model in list of fields' );
		$this->assertFalse( in_array( 'rev_content_format', $fields ), 'missing rev_content_format in list of fields' );
	}

	/**
	 * @covers Revision::getContentModel
	 */
	public function testGetContentModel() {
		try {
			$this->makeRevision( array( 'text' => 'hello hello.',
				'content_model' => CONTENT_MODEL_JAVASCRIPT ) );

			$this->fail( "Creating JavaScript content on a wikitext page should fail with "
				. "\$wgContentHandlerUseDB disabled" );
		} catch ( MWException $ex ) {
			$this->assertTrue( true ); // ok
		}
	}


	/**
	 * @covers Revision::getContentFormat
	 */
	public function testGetContentFormat() {
		try {
			// @todo change this to test failure on using a non-standard (but supported) format
			//       for a content model supported in the given location. As of 1.21, there are
			//       no alternative formats for any of the standard content models that could be
			//       used for this though.

			$this->makeRevision( array( 'text' => 'hello hello.',
				'content_model' => CONTENT_MODEL_JAVASCRIPT,
				'content_format' => 'text/javascript' ) );

			$this->fail( "Creating JavaScript content on a wikitext page should fail with "
				. "\$wgContentHandlerUseDB disabled" );
		} catch ( MWException $ex ) {
			$this->assertTrue( true ); // ok
		}
	}
}
