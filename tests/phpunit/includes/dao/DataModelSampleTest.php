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
		// init some cache
		$this->setMwGlobals( array(
			'wgMemc' => new HashBagOStuff,
		) );

		// setup db table
		$this->db->begin();
		if ( $this->db->tableExists( 'datamodel_lists' ) ) {
			$this->db->dropTable( 'datamodel_lists' );
		}
		if ( $this->db->tableExists( 'datamodel_sample' ) ) {
			$this->db->dropTable( 'datamodel_sample' );
		}
		global $IP;
		$this->db->sourceFile( "$IP/includes/dao/sql/datamodel_lists.sql" );
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

		// clear cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', 1, 1 );
		$wgMemc->delete( $key );

		// clear db
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sample' );
		$partition = $store->getPartition( 'datamodel_sample', 'shard', 1 );
		$partition->delete(
			array( 'shard' => 1 ),
			__METHOD__
		);
	}

	public function testInsert() {
		$this->sample->insert();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', 1, 1 );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sample' );
		$partition = $store->getPartition( 'datamodel_sample', 'shard', 1 );
		$row = $partition->select(
			DB_SLAVE,
			array( 'id', 'shard', 'title', 'email', 'visible', 'timestamp' ),
			array( 'id' => 1, 'shard' => 1 ),
			__METHOD__
		);
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testSaveInsert() {
		$this->sample->save();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', 1, 1 );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sample' );
		$partition = $store->getPartition( 'datamodel_sample', 'shard', 1 );
		$row = $partition->select(
			DB_SLAVE,
			array( 'id', 'shard', 'title', 'email', 'visible', 'timestamp' ),
			array( 'id' => 1, 'shard' => 1 ),
			__METHOD__
		);
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testGet() {
		$this->sample->insert();

		$this->assertEquals( $this->sample, DataModelSample::get( 1, 1 ) );
	}

	public function testUpdate() {
		$this->sample->insert();
		$sample = DataModelSample::get( 1, 1 );
		$sample->title = "Test #1, revised";
		$this->sample->update();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', 1, 1 );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sample' );
		$partition = $store->getPartition( 'datamodel_sample', 'shard', 1 );
		$row = $partition->select(
			DB_SLAVE,
			array( 'id', 'shard', 'title', 'email', 'visible', 'timestamp' ),
			array( 'id' => 1, 'shard' => 1 ),
			__METHOD__
		);
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testSaveUpdate() {
		$this->sample->insert();
		$sample = DataModelSample::get( 1, 1 );
		$sample->title = "Test #1, revised";
		$this->sample->save();

		// data in cache
		global $wgMemc;
		$key = wfMemcKey( 'DataModelSample', 'get', 1, 1 );
		$this->assertEquals( $this->sample, $wgMemc->get( $key ) );

		// data in db
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sample' );
		$partition = $store->getPartition( 'datamodel_sample', 'shard', 1 );
		$row = $partition->select(
			DB_SLAVE,
			array( 'id', 'shard', 'title', 'email', 'visible', 'timestamp' ),
			array( 'id' => 1, 'shard' => 1 ),
			__METHOD__
		);
		$this->assertEquals( get_object_vars( $this->sample ), get_object_vars( $row->fetchObject() ) );
	}

	public function testGetList() {
		for ( $i = 0; $i < 10; $i++ ) {
			$sample = clone( $this->sample );
			$sample->title = 'Title #'. ( $i + 1 );
			$sample->visible = $i % 2;
			$sample->insert();
		}

		$list = DataModelSample::getList( 'hidden', null, 0, 'DESC' );
		$this->assertEquals( count( $list ), 5 );
		$last = array_pop( $list );
		$this->assertEquals( $last->title, 'Title #9' );
	}
}
