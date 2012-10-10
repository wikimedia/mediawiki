<?php
/**
 * This class will test the datamodel sample.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 */
class DataModelSampleTest extends MediaWikiTestCase {
	protected $sample;

	protected $tablesUsed = array( 'datamodel_lists', 'datamodel_sample' );

	public function setUp() {
		parent::setUp();

		// init some cache
		$this->setMwGlobals( array(
			'wgMemc' => new HashBagOStuff,
		) );

		// define db backend
		global $wgDataModelBackendClass;
		$wgDataModelBackendClass = 'DataModelBackendLBFactory';

		// setup db tables
		$this->db->begin();
		if ( $this->db->tableExists( 'datamodel_lists' ) ) {
			$this->db->dropTable( 'datamodel_lists' );
		}
		if ( $this->db->tableExists( 'datamodel_sample' ) ) {
			$this->db->dropTable( 'datamodel_sample' );
		}
		global $IP;
		$this->db->sourceFile( "$IP/maintenance/archives/patch-datamodel_lists.sql" );
		$this->db->sourceFile( "$IP/includes/dao/sql/datamodel_sample.sql" );
		$this->db->commit();

		// init sample object
		$this->sample = new DataModelSample;
		$this->sample->shard = 1;
		$this->sample->title = "Test";
		$this->sample->email = "mmullie@wikimedia.org";
		$this->sample->visible = rand( 0, 1 );
	}

	public function tearDown() {
		unset( $this->sample );

		$list = DataModelSample::getList( 'all', null, 0, 'title' );
		foreach ( $list as $entry ) {
			$entry->delete();
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

	public function testSaveInsert() {
		$this->sample->save();

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

	public function testUpdate() {
		$this->sample->insert();
		$sample = DataModelSample::get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$sample->title = "Test #1, revised";
		$this->sample->update();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$row = DataModelSample::getBackend()->get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testSaveUpdate() {
		$this->sample->insert();
		$sample = DataModelSample::get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$sample->title = "Test #1, revised";
		$this->sample->save();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$row = DataModelSample::getBackend()->get( $this->sample->{DataModelSample::getIdColumn()}, $this->sample->{DataModelSample::getShardColumn()} );
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testGetList() {
		for ( $i = 0; $i < 10; $i++ ) {
			$sample = clone( $this->sample );
			$sample->title = 'Title #'. ( $i + 1 );
			$sample->visible = $i % 2;
			$sample->insert();
		}

		$list = DataModelSample::getList( 'hidden', null, 0, 'timestamp', 'DESC' );
		$this->assertEquals( count( $list ), 5 );
		$last = array_pop( $list );
		$this->assertEquals( $last->title, 'Title #9' );
	}
}
