<?php

class MigrateFileRepoLayoutTest extends MediaWikiTestCase {
	protected $tmpPrefix;
	protected $migratorMock;
	protected $tmpFilepath;
	protected $text = 'testing';

	protected function setUp() {
		parent::setUp();

		$filename = 'Foo.png';

		$this->tmpPrefix = $this->getNewTempDirectory();

		$backend = new FSFileBackend( [
			'name' => 'local-migratefilerepolayouttest',
			'wikiId' => wfWikiID(),
			'containerPaths' => [
				'migratefilerepolayouttest-original' => "{$this->tmpPrefix}-original",
				'migratefilerepolayouttest-public' => "{$this->tmpPrefix}-public",
				'migratefilerepolayouttest-thumb' => "{$this->tmpPrefix}-thumb",
				'migratefilerepolayouttest-temp' => "{$this->tmpPrefix}-temp",
				'migratefilerepolayouttest-deleted' => "{$this->tmpPrefix}-deleted",
			]
		] );

		$dbMock = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->getMock();

		$imageRow = new stdClass;
		$imageRow->img_name = $filename;
		$imageRow->img_sha1 = sha1( $this->text );

		$dbMock->expects( $this->any() )
			->method( 'select' )
			->will( $this->onConsecutiveCalls(
				new FakeResultWrapper( [ $imageRow ] ), // image
				new FakeResultWrapper( [] ), // image
				new FakeResultWrapper( [] ) // filearchive
			) );

		$repoMock = $this->getMock( 'LocalRepo',
			[ 'getMasterDB' ],
			[ [
				'name' => 'migratefilerepolayouttest',
				'backend' => $backend
			] ] );

		$repoMock
			->expects( $this->any() )
			->method( 'getMasterDB' )
			->will( $this->returnValue( $dbMock ) );

		$this->migratorMock = $this->getMock( 'MigrateFileRepoLayout', [ 'getRepo' ] );
		$this->migratorMock
			->expects( $this->any() )
			->method( 'getRepo' )
			->will( $this->returnValue( $repoMock ) );

		$this->tmpFilepath = TempFSFile::factory( 'migratefilelayout-test-', 'png' )->getPath();

		file_put_contents( $this->tmpFilepath, $this->text );

		$hashPath = $repoMock->getHashPath( $filename );

		$status = $repoMock->store(
			$this->tmpFilepath,
			'public',
			$hashPath . $filename,
			FileRepo::OVERWRITE
		);
	}

	protected function deleteFilesRecursively( $directory ) {
		foreach ( glob( $directory . '/*' ) as $file ) {
			if ( is_dir( $file ) ) {
				$this->deleteFilesRecursively( $file );
			} else {
				unlink( $file );
			}
		}

		rmdir( $directory );
	}

	protected function tearDown() {
		foreach ( glob( $this->tmpPrefix . '*' ) as $directory ) {
			$this->deleteFilesRecursively( $directory );
		}

		unlink( $this->tmpFilepath );

		parent::tearDown();
	}

	public function testMigration() {
		$this->migratorMock->loadParamsAndArgs(
			null,
			[ 'oldlayout' => 'name', 'newlayout' => 'sha1' ]
		);

		ob_start();

		$this->migratorMock->execute();

		ob_end_clean();

		$sha1 = sha1( $this->text );

		$expectedOriginalFilepath = $this->tmpPrefix
			. '-original/'
			. substr( $sha1, 0, 1 )
			. '/'
			. substr( $sha1, 1, 1 )
			. '/'
			. substr( $sha1, 2, 1 )
			. '/'
			. $sha1;

		$this->assertEquals(
			file_get_contents( $expectedOriginalFilepath ),
			$this->text,
			'New sha1 file should be exist and have the right contents'
		);

		$expectedPublicFilepath = $this->tmpPrefix . '-public/f/f8/Foo.png';

		$this->assertEquals(
			file_get_contents( $expectedPublicFilepath ),
			$this->text,
			'Existing name file should still and have the right contents'
		);
	}
}
