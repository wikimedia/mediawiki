<?php

/**
 * Test class for Revision storage.
 *
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 */
class RevisionStorageTest extends MediaWikiTestCase {

	var $the_page;

	function  __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge( $this->tablesUsed,
		                                 array( 'page',
		                                      'revision',
		                                      'text',

		                                      'recentchanges',
		                                      'logging',

		                                      'page_props',
		                                      'pagelinks',
		                                      'categorylinks',
		                                      'langlinks',
		                                      'externallinks',
		                                      'imagelinks',
		                                      'templatelinks',
		                                      'iwlinks' ) );
	}

	public function setUp() {
		if ( !$this->the_page ) {
			$this->the_page = $this->createPage( 'RevisionStorageTest_the_page', "just a dummy page" );
		}
	}

	protected function makeRevision( $props = null ) {
		if ( $props === null ) $props = array();

		if ( !isset( $props['content'] ) && !isset( $props['text'] ) ) $props['text'] = 'Lorem Ipsum';
		if ( !isset( $props['comment'] ) ) $props['comment'] = 'just a test';
		if ( !isset( $props['page'] ) ) $props['page'] = $this->the_page->getId();

		$rev = new Revision( $props );

		$dbw = wfgetDB( DB_MASTER );
		$rev->insertOn( $dbw );

		return $rev;
	}

	protected function createPage( $page, $text, $model = null ) {
		if ( is_string( $page ) ) $page = Title::newFromText( $page );
		if ( $page instanceof Title ) $page = new WikiPage( $page );

		if ( $page->exists() ) {
			$page->doDeleteArticle( "done" );
		}

		$page->doEdit( $text, "testing", EDIT_NEW );

		return $page;
	}

	protected function assertRevEquals( Revision $orig, Revision $rev = null ) {
		$this->assertNotNull( $rev, 'missing revision' );

		$this->assertEquals( $orig->getId(), $rev->getId() );
		$this->assertEquals( $orig->getPage(), $rev->getPage() );
		$this->assertEquals( $orig->getTimestamp(), $rev->getTimestamp() );
		$this->assertEquals( $orig->getUser(), $rev->getUser() );
		$this->assertEquals( $orig->getSha1(), $rev->getSha1() );
	}

	/**
	 * @covers Revision::__construct
	 */
	public function testConstructFromRow()
	{
		$orig = $this->makeRevision();

		$dbr = wfgetDB( DB_SLAVE );
		$res = $dbr->select( 'revision', '*', array( 'rev_id' => $orig->getId() ) );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = new Revision( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromRow
	 */
	public function testNewFromRow()
	{
		$orig = $this->makeRevision();

		$dbr = wfgetDB( DB_SLAVE );
		$res = $dbr->select( 'revision', '*', array( 'rev_id' => $orig->getId() ) );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}


	/**
	 * @covers Revision::newFromArchiveRow
	 */
	public function testNewFromArchiveRow()
	{
		$page = $this->createPage( 'RevisionStorageTest_testNewFromArchiveRow', 'Lorem Ipsum' );
		$orig = $page->getRevision();
		$page->doDeleteArticle( 'test Revision::newFromArchiveRow' );

		$dbr = wfgetDB( DB_SLAVE );
		$res = $dbr->select( 'archive', '*', array( 'ar_rev_id' => $orig->getId() ) );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromArchiveRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromId
	 */
	public function testNewFromId()
	{
		$orig = $this->makeRevision();

		$rev = Revision::newFromId( $orig->getId() );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::fetchRevision
	 */
	public function testFetchRevision()
	{
		$page = $this->createPage( 'RevisionStorageTest_testFetchRevision', 'one' );
		$id1 = $page->getRevision()->getId();

		$page->doEdit( 'two', 'second rev' );
		$id2 = $page->getRevision()->getId();

		$res = Revision::fetchRevision( $page->getTitle() );

		#note: order is unspecified
		$rows = array();
		while ( ( $row = $res->fetchObject() ) ) {
			$rows[ $row->rev_id ]= $row;
		}

		$row = $res->fetchObject();
		$this->assertEquals( 1, count($rows), 'expected exactly one revision' );
		$this->assertArrayHasKey( $id2, $rows, 'missing revision with id ' . $id2 );
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
	}

	/**
	 * @covers Revision::getPage
	 */
	public function testGetPage()
	{
		$page = $this->the_page;

		$orig = $this->makeRevision( array( 'page' => $page->getId() ) );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( $page->getId(), $rev->getPage() );
	}

	/**
	 * @covers Revision::getText
	 */
	public function testGetText()
	{
		$orig = $this->makeRevision( array( 'text' => 'hello hello.' ) );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( 'hello hello.', $rev->getText() );
	}

	/**
	 * @covers Revision::revText
	 */
	public function testRevText()
	{
		$orig = $this->makeRevision( array( 'text' => 'hello hello rev.' ) );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( 'hello hello rev.', $rev->revText() );
	}

	/**
	 * @covers Revision::getRawText
	 */
	public function testGetRawText()
	{
		$orig = $this->makeRevision( array( 'text' => 'hello hello raw.' ) );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( 'hello hello raw.', $rev->getRawText() );
	}
	/**
	 * @covers Revision::isCurrent
	 */
	public function testIsCurrent()
	{
		$page = $this->createPage( 'RevisionStorageTest_testIsCurrent', 'Lorem Ipsum' );
		$rev1 = $page->getRevision();

		# @todo: find out if this should be true
		# $this->assertTrue( $rev1->isCurrent() );

		$rev1x = Revision::newFromId( $rev1->getId() );
		$this->assertTrue( $rev1x->isCurrent() );

		$page->doEdit( 'Bla bla', 'second rev' );
		$rev2 = $page->getRevision();

		# @todo: find out if this should be true
		# $this->assertTrue( $rev2->isCurrent() );

		$rev1x = Revision::newFromId( $rev1->getId() );
		$this->assertFalse( $rev1x->isCurrent() );

		$rev2x = Revision::newFromId( $rev2->getId() );
		$this->assertTrue( $rev2x->isCurrent() );
	}

	/**
	 * @covers Revision::getPrevious
	 */
	public function testGetPrevious()
	{
		$page = $this->createPage( 'RevisionStorageTest_testGetPrevious', 'Lorem Ipsum testGetPrevious' );
		$rev1 = $page->getRevision();

		$this->assertNull( $rev1->getPrevious() );

		$page->doEdit( 'Bla bla', 'second rev testGetPrevious' );
		$rev2 = $page->getRevision();

		$this->assertNotNull( $rev2->getPrevious() );
		$this->assertEquals( $rev1->getId(), $rev2->getPrevious()->getId() );
	}

	/**
	 * @covers Revision::getNext
	 */
	public function testGetNext()
	{
		$page = $this->createPage( 'RevisionStorageTest_testGetNext', 'Lorem Ipsum testGetNext' );
		$rev1 = $page->getRevision();

		$this->assertNull( $rev1->getNext() );

		$page->doEdit( 'Bla bla', 'second rev testGetNext' );
		$rev2 = $page->getRevision();

		$this->assertNotNull( $rev1->getNext() );
		$this->assertEquals( $rev2->getId(), $rev1->getNext()->getId() );
	}

	/**
	 * @covers Revision::newNullRevision
	 */
	public function testNewNullRevision()
	{
		$page = $this->createPage( 'RevisionStorageTest_testNewNullRevision', 'some testing text' );
		$orig = $page->getRevision();

		$dbw = wfGetDB( DB_MASTER );
		$rev = Revision::newNullRevision( $dbw, $page->getId(), 'a null revision', false );

		$this->assertNotEquals( $orig->getId(), $rev->getId(), 'new null revision shold have a different id from the original revision' );
		$this->assertEquals( $orig->getTextId(), $rev->getTextId(), 'new null revision shold have the same text id as the original revision' );
		$this->assertEquals( 'some testing text', $rev->getText() );
	}
}
