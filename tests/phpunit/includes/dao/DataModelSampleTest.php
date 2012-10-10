<?php
/**
 * This class will test the datamodel sample.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 */
class DataModelSampleTest extends MediaWikiTestCase {
	protected $sample;

	protected $tablesUsed = array( 'datamodel_sample' );

	public function setUp() {
		parent::setUp();

		// init some volatile BagOStuff
		$this->setMwGlobals( array(
			'wgMemc' => new HashBagOStuff,
		) );

		// define db backend
		global $wgDataModelBackendClass;
		$wgDataModelBackendClass = 'DataModelBackendLBFactory';

		// setup db tables
		global $IP;
		$this->db->begin();
		$this->db->dropTable( 'datamodel_sample' );
		$this->db->sourceFile( "$IP/includes/dao/sql/datamodel_sample.sql" );
		$this->db->commit();

		// init sample object
		$this->sample = new DataModelSample;
		$this->sample->ds_shard = 1;
		$this->sample->ds_title = "Test";
		$this->sample->ds_email = "mmullie@wikimedia.org";
		$this->sample->ds_visible = rand( 0, 1 );
	}

	public function tearDown() {
		unset( $this->sample );

		$list = DataModelSample::getList( 'all', null, 0, 'title' );
		if ( $list ) {
			foreach ( $list as $entry ) {
				$entry->delete();
			}
		}
	}

	public function testInsert() {
		$this->sample->insert();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$row = DataModelSample::getBackend()->get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testUpdate() {
		$this->sample->insert();
		$sample = DataModelSample::get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$sample->ds_title = "Test #1, revised";
		$this->sample->update();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$row = DataModelSample::getBackend()->get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testGet() {
		$this->sample->insert();
		$this->assertEquals( $this->sample, DataModelSample::get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} ) );
	}

	public function testGetList() {
		$size = 60;
		$probability = 1 / 2;

		for ( $i = 0; $i < $size; $i++ ) {
			$sample = clone( $this->sample );
			$sample->ds_title = 'Title #'. ( $i + 1 );
			$sample->ds_visible = $i % ( 1 / $probability );
			$sample->insert();
		}

		// 1st batch
		$offset = 0;
		$list = DataModelSample::getList( 'hidden', null, $offset, 'title', 'ASC' );
		$this->assertEquals( $list->numRows(), DataModelSample::LIST_LIMIT );
		$first = $list->fetchObject();
		$this->assertEquals( $first->ds_title, 'Title #1' );

		// 2nd batch
		$offset = $list->nextOffset();
		$list = DataModelSample::getList( 'hidden', null, $offset, 'title', 'ASC' );
		$this->assertEquals( $list->numRows(), round( $size * $probability - DataModelSample::LIST_LIMIT ) );
		$first = $list->fetchObject();
		/*
		 * Note: logically, you might expect Title #51 here. However, these
		 * are ordered as string (not int), so 6, 7, 8 & 9 will come _after_
		 * 50-something. This accounts for the 4-digit difference with what
		 * you might expect at first glance ;)
		 */
		$this->assertEquals( $first->ds_title, 'Title #55' );
	}

	public function testGetCount() {
		$size = 10;
		$probability = 1 / 2;

		for ( $i = 0; $i < $size; $i++ ) {
			$sample = clone( $this->sample );
			$sample->ds_title = 'Title #'. ( $i + 1 );
			$sample->ds_visible = $i % ( 1 / $probability );
			$sample->insert();
		}

		$this->assertEquals( DataModelSample::getCount( 'hidden', null ), $size * $probability );
	}
}
