<?php

namespace MediaWiki\Tests\Maintenance;

use FindMissingFiles;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @covers \FindMissingFiles
 * @group Database
 */
class FindMissingFilesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FindMissingFiles::class;
	}

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::LocalFileRepo => [
				'class' => LocalRepo::class,
				'name' => 'local',
				'backend' => new FSFileBackend( [
					'name' => 'findmissingfiles-test-backend',
					'wikiId' => WikiMap::getCurrentWikiId(),
					'basePath' => $this->getNewTempDirectory(),
				] ),
				'url' => 'http://localhost/images',
			],
			MainConfigNames::ForeignFileRepos => [],
		] );
	}

	public static function provideExecute() {
		foreach ( [
			'old' => SCHEMA_COMPAT_OLD,
			'write both, read new' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			'new' => SCHEMA_COMPAT_NEW,
		] as $stageName => $stage ) {
			yield $stageName => [ $stage, [] ];
			yield "$stageName, with mtimeafter" => [ $stage, [ 'mtimeafter' => '20010115000000' ] ];
		}
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( int $migrationStage, array $options ) {
		$this->overrideConfigValue( MainConfigNames::FileSchemaMigrationStage, $migrationStage );
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$user = $this->getTestUser()->getUser();

		$present = new LocalFile( Title::makeTitle( NS_FILE, 'Present.png' ), $repo );
		$this->assertStatusGood( $present->upload(
			__DIR__ . '/../data/media/greyscale-na-png.png', 'comment', 'page text', 0, false, false, $user
		) );
		$title = Title::makeTitle( NS_FILE, 'Missing.png' );
		foreach ( [ 'greyscale-png.png', '1bit-png.png' ] as $src ) {
			$missing = new LocalFile( $title, $repo );
			$this->assertStatusGood( $missing->upload(
				__DIR__ . "/../data/media/$src", 'comment', 'page text', 0, false, false, $user
			) );
		}
		$this->runDeferredUpdates();

		// Remove the current and the archived version from storage only
		$currentPath = $missing->getPath();
		$oldPath = $missing->getHistory()[0]->getPath();
		$this->assertStatusGood( $repo->getBackend()->quickDelete( [ 'src' => $currentPath ] ) );
		$this->assertStatusGood( $repo->getBackend()->quickDelete( [ 'src' => $oldPath ] ) );

		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();

		$this->expectOutputString( "$currentPath\n$oldPath\n" );
	}
}
