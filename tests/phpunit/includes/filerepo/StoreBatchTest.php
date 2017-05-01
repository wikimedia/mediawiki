<?php

/**
 * @group FileRepo
 * @group medium
 */
class StoreBatchTest extends MediaWikiTestCase {

	protected $createdFiles;
	protected $date;
	/** @var FileRepo */
	protected $repo;

	protected function setUp() {
		global $wgFileBackends;
		parent::setUp();

		# Forge a FileRepo object to not have to rely on local wiki settings
		$tmpPrefix = $this->getNewTempDirectory();
		if ( $this->getCliArg( 'use-filebackend' ) ) {
			$name = $this->getCliArg( 'use-filebackend' );
			$useConfig = [];
			foreach ( $wgFileBackends as $conf ) {
				if ( $conf['name'] == $name ) {
					$useConfig = $conf;
				}
			}
			$useConfig['lockManager'] = LockManagerGroup::singleton()->get( $useConfig['lockManager'] );
			unset( $useConfig['fileJournal'] );
			$useConfig['name'] = 'local-testing'; // swap name
			$class = $useConfig['class'];
			$backend = new $class( $useConfig );
		} else {
			$backend = new FSFileBackend( [
				'name' => 'local-testing',
				'wikiId' => wfWikiID(),
				'containerPaths' => [
					'unittests-public' => "{$tmpPrefix}/public",
					'unittests-thumb' => "{$tmpPrefix}/thumb",
					'unittests-temp' => "{$tmpPrefix}/temp",
					'unittests-deleted' => "{$tmpPrefix}/deleted",
				]
			] );
		}
		$this->repo = new FileRepo( [
			'name' => 'unittests',
			'backend' => $backend
		] );

		$this->date = gmdate( "YmdHis" );
		$this->createdFiles = [];
	}

	protected function tearDown() {
		// Delete files
		$this->repo->cleanupBatch( $this->createdFiles );
		parent::tearDown();
	}

	/**
	 * Store a file or virtual URL source into a media file name.
	 *
	 * @param string $originalName The title of the image
	 * @param string $srcPath The filepath or virtual URL
	 * @param int $flags Flags to pass into repo::store().
	 * @return Status
	 */
	private function storeit( $originalName, $srcPath, $flags ) {
		$hashPath = $this->repo->getHashPath( $originalName );
		$dstRel = "$hashPath{$this->date}!$originalName";
		$dstUrlRel = $hashPath . $this->date . '!' . rawurlencode( $originalName );

		$result = $this->repo->store( $srcPath, 'temp', $dstRel, $flags );
		$result->value = $this->repo->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;
		$this->createdFiles[] = $result->value;

		return $result;
	}

	/**
	 * Test storing a file using different flags.
	 *
	 * @param string $fn The title of the image
	 * @param string $infn The name of the file (in the filesystem)
	 * @param string $otherfn The name of the different file (in the filesystem)
	 * @param bool $fromrepo 'true' if we want to copy from a virtual URL out of the Repo.
	 */
	private function storecohort( $fn, $infn, $otherfn, $fromrepo ) {
		$f = $this->storeit( $fn, $infn, 0 );
		$this->assertTrue( $f->isOK(), 'failed to store a new file' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		if ( $fromrepo ) {
			$f = $this->storeit( "Other-$fn", $infn, FileRepo::OVERWRITE );
			$infn = $f->value;
		}
		// This should work because we're allowed to overwrite
		$f = $this->storeit( $fn, $infn, FileRepo::OVERWRITE );
		$this->assertTrue( $f->isOK(), 'We should be allowed to overwrite' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		// This should fail because we're overwriting.
		$f = $this->storeit( $fn, $infn, 0 );
		$this->assertFalse( $f->isOK(), 'We should not be allowed to overwrite' );
		$this->assertEquals( $f->failCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		// This should succeed because we're overwriting the same content.
		$f = $this->storeit( $fn, $infn, FileRepo::OVERWRITE_SAME );
		$this->assertTrue( $f->isOK(), 'We should be able to overwrite the same content' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		// This should fail because we're overwriting different content.
		if ( $fromrepo ) {
			$f = $this->storeit( "Other-$fn", $otherfn, FileRepo::OVERWRITE );
			$otherfn = $f->value;
		}
		$f = $this->storeit( $fn, $otherfn, FileRepo::OVERWRITE_SAME );
		$this->assertFalse( $f->isOK(), 'We should not be allowed to overwrite different content' );
		$this->assertEquals( $f->failCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
	}

	/**
	 * @covers FileRepo::store
	 */
	public function teststore() {
		global $IP;
		$this->storecohort(
			"Test1.png",
			"$IP/tests/phpunit/data/filerepo/wiki.png",
			"$IP/tests/phpunit/data/filerepo/video.png",
			false
		);
		$this->storecohort(
			"Test2.png",
			"$IP/tests/phpunit/data/filerepo/wiki.png",
			"$IP/tests/phpunit/data/filerepo/video.png",
			true
		);
	}
}
