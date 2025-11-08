<?php

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\WikiMap\WikiMap;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MigrateFileRepoLayout
 */
class MigrateFileRepoLayoutTest extends MediaWikiIntegrationTestCase {
	/** @var string */
	protected $tmpPrefix;
	/** @var MigrateFileRepoLayout&MockObject */
	protected $migratorMock;
	/** @var string */
	protected $tmpFilepath;
	private const TEXT = 'testing';

	protected function setUp(): void {
		parent::setUp();

		$filename = 'Foo.png';

		$this->tmpPrefix = $this->getNewTempDirectory();

		$backend = new FSFileBackend( [
			'name' => 'local-migratefilerepolayouttest',
			'wikiId' => WikiMap::getCurrentWikiId(),
			'containerPaths' => [
				'migratefilerepolayouttest-original' => "{$this->tmpPrefix}-original",
				'migratefilerepolayouttest-public' => "{$this->tmpPrefix}-public",
				'migratefilerepolayouttest-thumb' => "{$this->tmpPrefix}-thumb",
				'migratefilerepolayouttest-temp' => "{$this->tmpPrefix}-temp",
				'migratefilerepolayouttest-deleted' => "{$this->tmpPrefix}-deleted",
			]
		] );

		$dbMock = $this->createMock( IDatabase::class );

		$imageRow = (object)[
			'img_name' => $filename,
			'img_sha1' => sha1( self::TEXT ),
		];

		$dbMock->method( 'select' )
			->willReturnOnConsecutiveCalls(
				new FakeResultWrapper( [ $imageRow ] ), // image
				new FakeResultWrapper( [] ), // image
				new FakeResultWrapper( [] ) // filearchive
			);
		$dbMock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbMock ) );

		$repoMock = $this->getMockBuilder( LocalRepo::class )
			->onlyMethods( [ 'getPrimaryDB', 'getReplicaDB' ] )
			->setConstructorArgs( [ [
					'name' => 'migratefilerepolayouttest',
					'backend' => $backend
				] ] )
			->getMock();

		$repoMock
			->method( 'getPrimaryDB' )
			->willReturn( $dbMock );
		$replicaDB = $this->createMock( IDatabase::class );
		$replicaDB->method( 'getSessionLagStatus' )->willReturn( [ 'lag' => 0, 'since' => time() ] );
		$repoMock->method( 'getReplicaDB' )->willReturn( $replicaDB );

		$this->migratorMock = $this->getMockBuilder( MigrateFileRepoLayout::class )
			->onlyMethods( [ 'getRepo' ] )->getMock();
		$this->migratorMock
			->method( 'getRepo' )
			->willReturn( $repoMock );

		$this->tmpFilepath = TempFSFile::factory(
			'migratefilelayout-test-', 'png', wfTempDir() )->getPath();

		file_put_contents( $this->tmpFilepath, self::TEXT );

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

	protected function tearDown(): void {
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

		$sha1 = sha1( self::TEXT );

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
			self::TEXT,
			file_get_contents( $expectedOriginalFilepath ),
			'New sha1 file should be exist and have the right contents'
		);

		$expectedPublicFilepath = $this->tmpPrefix . '-public/f/f8/Foo.png';

		$this->assertEquals(
			self::TEXT,
			file_get_contents( $expectedPublicFilepath ),
			'Existing name file should still and have the right contents'
		);
	}
}
