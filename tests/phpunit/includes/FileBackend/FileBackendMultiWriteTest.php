<?php

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FileBackendMultiWrite;
use Wikimedia\FileBackend\MemoryFileBackend;
use Wikimedia\TestingAccessWrapper;

/**
 * @group FileRepo
 * @group FileBackend
 * @covers \Wikimedia\FileBackend\FileBackendMultiWrite
 */
class FileBackendMultiWriteTest extends MediaWikiIntegrationTestCase {
	public function testReadAffinity() {
		$be = TestingAccessWrapper::newFromObject(
			new FileBackendMultiWrite( [
				'name' => 'localtesting',
				'wikiId' => WikiMap::getCurrentWikiId() . mt_rand(),
				'backends' => [
					[ // backend 0
						'name' => 'multitesting0',
						'class' => MemoryFileBackend::class,
						'isMultiMaster' => false,
						'readAffinity' => true
					],
					[ // backend 1
						'name' => 'multitesting1',
						'class' => MemoryFileBackend::class,
						'isMultiMaster' => true
					]
				]
			] )
		);

		$this->assertSame(
			1,
			$be->getReadIndexFromParams( [ 'latest' => 1 ] ),
			'Reads with "latest" flag use backend 1'
		);
		$this->assertSame(
			0,
			$be->getReadIndexFromParams( [ 'latest' => 0 ] ),
			'Reads without "latest" flag use backend 0'
		);

		$p = 'container/test-cont/file.txt';
		$be->backends[0]->quickCreate( [
			'dst' => "mwstore://multitesting0/$p", 'content' => 'cattitude' ] );
		$be->backends[1]->quickCreate( [
			'dst' => "mwstore://multitesting1/$p", 'content' => 'princess of power' ] );

		$this->assertEquals(
			'cattitude',
			$be->getFileContents( [ 'src' => "mwstore://localtesting/$p" ] ),
			"Non-latest read came from backend 0"
		);
		$this->assertEquals(
			'princess of power',
			$be->getFileContents( [ 'src' => "mwstore://localtesting/$p", 'latest' => 1 ] ),
			"Latest read came from backend1"
		);
	}

	public function testAsyncWrites() {
		$deferredUpdates = [];
		$be = TestingAccessWrapper::newFromObject(
			new FileBackendMultiWrite( [
				'name' => 'localtesting',
				'wikiId' => WikiMap::getCurrentWikiId() . mt_rand(),
				'backends' => [
					[ // backend 0
						'name' => 'multitesting0',
						'class' => MemoryFileBackend::class,
						'isMultiMaster' => false
					],
					[ // backend 1
						'name' => 'multitesting1',
						'class' => MemoryFileBackend::class,
						'isMultiMaster' => true
					]
				],
				'replication' => 'async',
				'asyncHandler' => static function ( $update ) use ( &$deferredUpdates ) {
					$deferredUpdates[] = $update;
				}
			] )
		);

		$cleanup = DeferredUpdates::preventOpportunisticUpdates();

		$p = 'container/test-cont/file.txt';
		$be->quickCreate( [
			'dst' => "mwstore://localtesting/$p", 'content' => 'cattitude' ] );

		$this->assertFalse(
			$be->backends[0]->getFileContents( [ 'src' => "mwstore://multitesting0/$p" ] ),
			"File not yet written to backend 0"
		);
		$this->assertEquals(
			'cattitude',
			$be->backends[1]->getFileContents( [ 'src' => "mwstore://multitesting1/$p" ] ),
			"File already written to backend 1"
		);

		foreach ( $deferredUpdates as $update ) {
			$update();
		}

		$this->assertEquals(
			'cattitude',
			$be->backends[0]->getFileContents( [ 'src' => "mwstore://multitesting0/$p" ] ),
			"File now written to backend 0"
		);
	}
}
