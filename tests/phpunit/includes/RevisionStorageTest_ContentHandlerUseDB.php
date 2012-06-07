<?php

/**
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 */
class RevisionTest_ContentHandlerUseDB extends RevisionStorageTest {
	var $saveContentHandlerNoDB = null;

	function setUp() {
		global $wgContentHandlerUseDB;

		$this->saveContentHandlerNoDB = $wgContentHandlerUseDB;

		$wgContentHandlerUseDB = false;

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

	function tearDown() {
		global $wgContentHandlerUseDB;

		parent::tearDown();

		$wgContentHandlerUseDB = $this->saveContentHandlerNoDB;
	}

	/**
	 * @covers Revision::selectFields
	 */
	public function testSelectFields()
	{
		$fields = Revision::selectFields();

		$this->assertTrue( in_array( 'rev_id', $fields ), 'missing rev_id in list of fields');
		$this->assertTrue( in_array( 'rev_page', $fields ), 'missing rev_page in list of fields');
		$this->assertTrue( in_array( 'rev_timestamp', $fields ), 'missing rev_timestamp in list of fields');
		$this->assertTrue( in_array( 'rev_user', $fields ), 'missing rev_user in list of fields');

		$this->assertFalse( in_array( 'rev_content_model', $fields ), 'missing rev_content_model in list of fields');
		$this->assertFalse( in_array( 'rev_content_format', $fields ), 'missing rev_content_format in list of fields');
	}

	/**
	 * @covers Revision::getContentModel
	 */
	public function testGetContentModel()
	{
		$orig = $this->makeRevision( array( 'text' => 'hello hello.', 'content_model' => CONTENT_MODEL_JAVASCRIPT ) );
		$rev = Revision::newFromId( $orig->getId() );

		//NOTE: database fields for the content_model are disabled, so the model name is not retained.
		//      We expect to get the default here instead of what was suppleid when creating the revision.
		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $rev->getContentModel() );
	}


	/**
	 * @covers Revision::getContentFormat
	 */
	public function testGetContentFormat()
	{
		$orig = $this->makeRevision( array( 'text' => 'hello hello.', 'content_model' => CONTENT_MODEL_JAVASCRIPT, 'content_format' => 'text/javascript' ) );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( CONTENT_FORMAT_WIKITEXT, $rev->getContentFormat() );
	}

}


