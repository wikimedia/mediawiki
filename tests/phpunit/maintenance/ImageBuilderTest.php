<?php

namespace MediaWiki\Tests\Maintenance;

use ImageBuilder;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @covers \ImageBuilder
 * @group Database
 */
class ImageBuilderTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ImageBuilder::class;
	}

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::LocalFileRepo => [
				'class' => LocalRepo::class,
				'name' => 'local',
				'backend' => new FSFileBackend( [
					'name' => 'imagebuilder-test-backend',
					'wikiId' => WikiMap::getCurrentWikiId(),
					'basePath' => $this->getNewTempDirectory(),
				] ),
				'url' => 'http://localhost/images',
			],
			MainConfigNames::ForeignFileRepos => [],
		] );
	}

	public static function provideBuild() {
		yield 'old' => [
			SCHEMA_COMPAT_OLD,
			"Processing image...\nFinished image... 0 of 1 rows updated\n" .
			"Processing oldimage...\nFinished oldimage... 0 of 1 rows updated\n",
		];
		yield 'write both, read new' => [
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			"Processing file...\nFinished file... 0 of 1 rows updated\n",
		];
		yield 'new' => [
			SCHEMA_COMPAT_NEW,
			"Processing file...\nFinished file... 0 of 1 rows updated\n",
		];
	}

	/**
	 * @dataProvider provideBuild
	 */
	public function testBuild( int $migrationStage, string $expectedOutput ) {
		$this->overrideConfigValue( MainConfigNames::FileSchemaMigrationStage, $migrationStage );
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$title = Title::makeTitle( NS_FILE, 'ImageBuilderTest.png' );
		foreach ( [ 'greyscale-png.png', '1bit-png.png' ] as $src ) {
			$file = new LocalFile( $title, $repo );
			$this->assertStatusGood( $file->upload(
				__DIR__ . "/../data/media/$src", 'comment', 'page text', 0, false, false,
				$this->getTestUser()->getUser()
			) );
		}

		$this->maintenance->execute();

		$this->expectOutputString( $expectedOutput );
	}
}
