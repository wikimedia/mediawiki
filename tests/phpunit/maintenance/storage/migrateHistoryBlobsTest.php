<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @covers MigrateHistoryBlobs
 */
class MigrateHistoryBlobsTest extends MediaWikiTestCase {

	protected $revisions = [];

	protected function tearDown() {
		if ( $this->db->tableExists( 'cur' ) ) {
			$this->db->query( 'DROP TABLE ' . $this->db->tableName( 'cur' ) );
		}
		parent::tearDown();
	}

	protected function setupRevisions( $cluster = null ) {
		$temporary = $this->usesTemporaryTables() ? 'TEMPORARY' : '';
		$this->db->query( "CREATE $temporary TABLE " . $this->db->tableName( 'cur' )
			. ' ( cur_id INTEGER NOT NULL, cur_text VARCHAR(20) NOT NULL )' );
		$this->db->insert( 'cur', [ 'cur_id' => 42, 'cur_text' => 'Cur revision 42' ], __METHOD__ );
		$this->db->insert( 'cur', [ 'cur_id' => 43, 'cur_text' => 'Cur revision 43' ], __METHOD__ );

		$res = $this->insertPage( 'MigrateHistoryBlobsTest', 'Base revision' );
		$id = $res['id'];
		$rev = (array)$this->db->selectRow( 'revision', '*', [ 'rev_page' => $res['id'] ], __METHOD__ );
		unset( $rev['rev_id'] );

		$this->db->insert( 'text', [
			'old_text' => 'Plain text revision',
			'old_flags' => 'utf-8'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['plain'] = $this->db->insertId();

		$concat = new ConcatenatedGzipHistoryBlob();
		$concat->setText( 'CGZ revision 1; ' . str_repeat( 'x', 100 ) );
		$hash2 = $concat->addItem( 'CGZ revision 2' );

		$this->db->insert( 'text', [
			'old_text' => serialize( $concat ),
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['cgz1'] = $this->db->insertId();

		$this->db->insert( 'text', [
			'old_text' => serialize( new HistoryBlobStub( $hash2, $rev['rev_text_id'] ) ),
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['cgz2'] = $this->db->insertId();

		$this->db->insert( 'text', [
			'old_text' => serialize( new HistoryBlobCurStub( 42 ) ),
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['cur'] = $this->db->insertId();

		$diff = "O:15:\"DiffHistoryBlob\":1:{s:11:\"mCompressed\";s:99:\"K\xB42\xB6\xAA.\xB62\xB5RJ\xC9LK"
			. "+V\xB2N\xB42\xB2\xAA\xCE\xB42\xB0.\xB62\x02\x8A2@\x01#\xBF\x0BP^!(\xB5,\xB383?O\xC1P\xC9:\xD3"
			. "\xCA\x10\xAA\xC8\x92u\x9A\x16?\x16EFJ\xD6\xB5\xC5@\x0B\x94r\x13\x0B\x94\xAC\xC1,\x03\x1DC\x10"
			. "\xCB\x1Ch_jZbiN\x09\xC8\x1C\x03\xEBZ\x00\";}";

		$this->db->insert( 'text', [
			'old_text' => $diff,
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['diff1'] = $this->db->insertId();

		$this->db->insert( 'text', [
			'old_text' => serialize( new HistoryBlobStub( 1, $rev['rev_text_id'] ) ),
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['diff2'] = $this->db->insertId();

		// Crazy that this works.
		$mock = $this->getMockBuilder( stdClass::class )
			->setMethods( [ 'getText' ] )
			->getMock();
		$mock->method( 'getText' )->willReturn( 'Crazy serialized mock' );
		$this->db->insert( 'text', [
			'old_text' => serialize( $mock ),
			'old_flags' => 'utf-8,object'
		], __METHOD__ );
		$rev['rev_text_id'] = $this->db->insertId();
		$this->db->insert( 'revision', $rev, __METHOD__ );
		$this->revisions['unknown'] = $this->db->insertId();

		if ( $cluster ) {
			$lb = wfGetLBFactory()->getExternalLB( $cluster );
			$esdbw = $lb->getConnection( DB_MASTER );
			$table = $esdbw->getLBInfo( 'blobs table' );
			if ( $table === null ) {
				$table = 'blobs';
			}

			$esdbw->insert( $table, [
				'blob_text' => 'External plain text revision',
			], __METHOD__ );
			$id = $esdbw->insertId();
			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id",
				'old_flags' => 'utf-8,external'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['external'] = $this->db->insertId();

			$concat = new ConcatenatedGzipHistoryBlob();
			$hash1 = $concat->addItem( 'External CGZ revision 1' );
			$hash2 = $concat->addItem( 'External CGZ revision 2' );

			$esdbw->insert( $table, [
				'blob_text' => serialize( $concat ),
			], __METHOD__ );
			$id = $esdbw->insertId();
			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id/$hash1",
				'old_flags' => 'utf-8,external'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extcgz1'] = $this->db->insertId();

			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id/$hash2",
				'old_flags' => 'utf-8,external'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extcgz2'] = $this->db->insertId();

			$concat = new ConcatenatedGzipHistoryBlob();
			$concat->setText( 'External CGZ object revision 1' );
			$hash2 = $concat->addItem( 'External CGZ object revision 2' );

			$esdbw->insert( $table, [
				'blob_text' => serialize( $concat ),
			], __METHOD__ );
			$id = $esdbw->insertId();
			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id",
				'old_flags' => 'utf-8,external,object'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extcgzobj1'] = $this->db->insertId();

			$this->db->insert( 'text', [
				'old_text' => serialize( new HistoryBlobStub( $hash2, $rev['rev_text_id'] ) ),
				'old_flags' => 'utf-8,object'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extcgzobj2'] = $this->db->insertId();

			$esdbw->insert( $table, [
				'blob_text' => $diff,
			], __METHOD__ );
			$id = $esdbw->insertId();
			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id",
				'old_flags' => 'utf-8,external,object'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extdiffobj1'] = $this->db->insertId();

			$this->db->insert( 'text', [
				'old_text' => serialize( new HistoryBlobStub( 1, $rev['rev_text_id'] ) ),
				'old_flags' => 'utf-8,object'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extdiffobj2'] = $this->db->insertId();

			$esdbw->insert( $table, [
				'blob_text' => serialize( new HistoryBlobCurStub( 43 ) ),
			], __METHOD__ );
			$id = $esdbw->insertId();
			$this->db->insert( 'text', [
				'old_text' => "DB://$cluster/$id",
				'old_flags' => 'utf-8,external,object'
			], __METHOD__ );
			$rev['rev_text_id'] = $this->db->insertId();
			$this->db->insert( 'revision', $rev, __METHOD__ );
			$this->revisions['extunknown'] = $this->db->insertId();
		}
	}

	protected function checkRevisions( $note ) {
		$data = [
			'plain' => 'Plain text revision',
			'cgz1' => 'CGZ revision 1; ' . str_repeat( 'x', 100 ),
			'cgz2' => 'CGZ revision 2',
			'cur' => 'Cur revision 42',
			'diff1' => 'Diff Revision 1',
			'diff2' => 'Diff Revision 2',
			'unknown' => 'Crazy serialized mock',

			'external' => 'External plain text revision',
			'extcgz1' => 'External CGZ revision 1',
			'extcgz2' => 'External CGZ revision 2',
			'extcgzobj1' => 'External CGZ object revision 1',
			'extcgzobj2' => 'External CGZ object revision 2',
			'extdiffobj1' => 'Diff Revision 1',
			'extdiffobj2' => 'Diff Revision 2',
			'extunknown' => 'Cur revision 43',
		];
		foreach ( $data as $rev => $text ) {
			if ( isset( $this->revisions[$rev] ) ) {
				$this->assertSame(
					$text, Revision::newFromId( $this->revisions[$rev] )->getSerializedData(), $note
				);
			}
		}
	}

	private function doTestMigrateHistoryBlobs( $args ) {
		$this->checkRevisions( 'sanity check' );

		$maintenance = $this->getMockBuilder( MigrateHistoryBlobs::class )
			->setMethods( [ 'output' ] )
			->getMock();
		$maintenance->method( 'output' )->willReturn( null ); // Suppress normal output
		$maintenance->loadParamsAndArgs( null, [ 'for-real' => true, 'unknown-ok' => true ] + $args );
		$maintenance->setConfig( MediaWikiServices::getInstance()->getMainConfig() );
		$maintenance->execute();

		$this->checkRevisions( 'revisions readable' );

		$this->assertEmpty( iterator_to_array( $this->db->select(
			[ 'revision', 'text' ],
			[ 'old_id', 'old_flags' ],
			[
				'rev_id' => array_values( $this->revisions ),
				'old_flags' . $this->db->buildLike( $this->db->anyString(), 'object', $this->db->anyString() ),
			],
			__METHOD__,
			[],
			[ 'text' => [ 'JOIN', 'rev_text_id = old_id' ] ]
		) ), 'No text row has the "object" flag' );
		$this->assertEmpty( iterator_to_array( $this->db->select(
			[ 'revision', 'text' ],
			[ 'old_id', 'old_text' ],
			[
				'rev_id' => array_values( $this->revisions ),
				'old_text' . $this->db->buildLike( 'O:', $this->db->anyString() ),
			],
			__METHOD__,
			[],
			[ 'text' => [ 'JOIN', 'rev_text_id = old_id' ] ]
		) ), 'No text row looks like an object' );
	}

	public function testMigrateHistoryBlobs() {
		$this->setMwGlobals( 'wgDefaultExternalStore', false );
		$this->overrideMwServices();

		$this->setupRevisions( null );
		$this->doTestMigrateHistoryBlobs( [ 'decompress' => true ] );
	}

	public function testMigrateHistoryBlobs_externals() {
		global $wgDefaultExternalStore;

		$this->setMwGlobals( 'wgCompressRevisions', false );

		$cluster = null;
		foreach ( (array)$wgDefaultExternalStore as $location ) {
			if ( substr( $location, 0, 5 ) === 'DB://' ) {
				$cluster = substr( $location, 5 );
				break;
			}
		}
		if ( !$cluster ) {
			$this->markTestSkipped( 'ExternalStore is not enabled' );
		}

		$this->setupRevisions( $cluster );
		$this->doTestMigrateHistoryBlobs( [] );

		$lb = wfGetLBFactory()->getExternalLB( $cluster );
		$esdbw = $lb->getConnection( DB_MASTER );
		$table = $esdbw->getLBInfo( 'blobs table' );
		if ( $table === null ) {
			$table = 'blobs';
		}

		$blobs = [];
		$res = $this->db->select(
			[ 'revision', 'text' ],
			[ 'old_text' ],
			[
				'rev_id' => array_values( $this->revisions ),
				'old_flags' . $this->db->buildLike( $this->db->anyString(), 'external', $this->db->anyString() )
			],
			__METHOD__,
			[],
			[ 'text' => [ 'JOIN', 'rev_text_id = old_id' ] ]
		);
		foreach ( $res as $row ) {
			$parts = explode( '/', $row->old_text );
			$blobs[$parts[3]] = true;
		}
		$this->assertEmpty( iterator_to_array( $esdbw->select(
			$table,
			[ 'blob_id', 'blob_text' ],
			[
				'blob_id' => array_keys( $blobs ),
				'blob_text' . $esdbw->buildLike( 'O:', $esdbw->anyString() ),
			],
			__METHOD__
		) ), 'No referenced blob row looks like an object' );

		$expect = [
			'plain' => 'utf-8',
			'cgz1' => 'utf-8,external',
			'cgz2' => 'utf-8,external',
			'cur' => 'utf-8',
			'diff1' => 'utf-8,external',
			'diff2' => 'utf-8,external',
			'unknown' => 'utf-8',

			'external' => 'utf-8,external',
			'extcgz1' => 'utf-8,external',
			'extcgz2' => 'utf-8,external',
			'extcgzobj1' => 'utf-8,external',
			'extcgzobj2' => 'utf-8,external',
			'extdiffobj1' => 'utf-8,external',
			'extdiffobj2' => 'utf-8,external',
			'extunknown' => 'utf-8',
		];
		foreach ( $expect as $rev => $flags ) {
			$this->assertSame( $flags, $this->db->selectField(
				[ 'revision', 'text' ],
				'old_flags',
				[ 'rev_id' => $this->revisions[$rev] ],
				__METHOD__,
				[],
				[ 'text' => [ 'JOIN', 'rev_text_id = old_id' ] ]
			), "externals have expected flags ($rev)" );
		}

		$expect = [
			'cgz1' => 3,
			'cgz2' => 3,
			'diff1' => 3,
			'diff2' => 3,

			'external' => 2,
			'extcgz1' => 3,
			'extcgz2' => 3,
			'extcgzobj1' => 3,
			'extcgzobj2' => 3,
			'extdiffobj1' => 3,
			'extdiffobj2' => 3,
		];
		foreach ( $expect as $rev => $parts ) {
			$this->assertRegExp(
				'<^DB://' . implode( '/', array_fill( 0, $parts, '[^/]+' ) ) . '$>',
				$this->db->selectField(
					[ 'revision', 'text' ],
					'old_text',
					[ 'rev_id' => $this->revisions[$rev] ],
					__METHOD__,
					[],
					[ 'text' => [ 'JOIN', 'rev_text_id = old_id' ] ]
				),
				"externals have expected number of path components ($rev)"
			);
		}
	}

}
