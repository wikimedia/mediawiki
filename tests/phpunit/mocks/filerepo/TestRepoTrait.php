<?php

namespace MediaWiki\Tests\FileRepo;

use FileBackend;
use FSFileBackend;
use LocalRepo;
use LogicException;
use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\Assert;
use RepoGroup;
use Title;

trait TestRepoTrait {

	private static ?string $mockRepoTraitDir = null;

	/**
	 * Initializes a mock repository in a temporary directory.
	 * Must only be called in addDbDataOnce().
	 * Must be paired with a call to destroyTestRepo() in tearDownAfterClass().
	 */
	private function initTestRepoGroup(): RepoGroup {
		if ( self::$mockRepoTraitDir ) {
			throw new LogicException( 'Mock repo already initialized. ' .
				'initTestRepogroup() must only be called from addDBDataOnce() ' .
				'and must be paired with a call to destroyTestRepo() in ' .
				'tearDownAfterClass().' );
		}

		$tmp = tempnam( wfTempDir(), 'mw-mock-repo-' );

		// tmpnam creates a file, we need a directory
		if ( file_exists( $tmp ) ) {
			unlink( $tmp );
		}
		mkdir( $tmp );

		self::$mockRepoTraitDir = $tmp;
		$this->installTestRepoGroup();
		return $this->getTestRepoGroup();
	}

	private function getTestRepoGroup(): RepoGroup {
		if ( self::$mockRepoTraitDir === null ) {
			throw new LogicException( 'Mock repo not initialized. ' .
				'Call initTestRepo() from addDBDataOnce() and a call ' .
				'to destroyTestRepo() in tearDownAfterClass().' );
		}

		return $this->getServiceContainer()->getRepoGroup();
	}

	private function getTestRepo(): LocalRepo {
		return $this->getTestRepoGroup()->getLocalRepo();
	}

	/**
	 * Destroys a mock repo.
	 * Should be called in tearDownAfterClass()
	 */
	private static function destroyTestRepo() {
		if ( !self::$mockRepoTraitDir ) {
			return;
		}

		$dir = self::$mockRepoTraitDir;

		if ( !is_dir( $dir ) ) {
			return;
		}

		if ( !str_starts_with( $dir, wfTempDir() ) ) {
			throw new \InvalidArgumentException( "Not in temp dir: $dir" );
		}

		$name = basename( $dir );
		if ( !str_starts_with( $name, 'mw-mock-repo-' ) ) {
			throw new \InvalidArgumentException( "Not a mock repo dir: $dir" );
		}

		// TODO: Recursively delete the directory. Scary!

		self::$mockRepoTraitDir = null;
	}

	private function installTestRepoGroup( array $options = [] ) {
		$this->setService( 'RepoGroup', $this->createTestRepoGroup( $options ) );
	}

	private function createTestRepoGroup( $options = [], ?MediaWikiServices $services = null ) {
		$services ??= $this->getServiceContainer();
		$localFileRepo = $this->getLocalFileRepoConfig( $options );

		$mimeAnalyzer = $services->getMimeAnalyzer();

		$repoGroup = new RepoGroup(
			$localFileRepo,
			[],
			$services->getMainWANObjectCache(),
			$mimeAnalyzer
		);
		return $repoGroup;
	}

	private function getLocalFileRepoConfig( $options = [] ): array {
		if ( self::$mockRepoTraitDir === null ) {
			throw new LogicException( 'Mock repo not initialized. ' .
				'Call initTestRepo() from addDBDataOnce() and a call ' .
				'to destroyTestRepo() in tearDownAfterClass().' );
		}

		$options['directory'] ??= self::$mockRepoTraitDir;
		$options['scriptDirUrl'] ??= '/w';

		$scriptPath = $options['scriptDirUrl'];
		$dir = $options['directory'];

		$info = $options + [
			"class" => "LocalRepo",
			"name" => "test",
			"domainId" => "mywiki",
			"directory" => $dir,
			"scriptDirUrl" => $scriptPath,
			"favicon" => "/favicon.ico",
			"url" => "$scriptPath/images",
			"hashLevels" => 2,
			"abbrvThreshold" => 16,
			"thumbScriptUrl" => "$scriptPath/thumb.php",
			"transformVia404" => false,
			"deletedDir" => "$dir/deleted",
			"deletedHashLevels" => 0,
			"updateCompatibleMetadata" => false,
			"reserializeMetadata" => false,
			"backend" => 'local-backend'
		];

		if ( !$info['backend'] instanceof FileBackend ) {
			$info['backend'] = $this->createFileBackend( $info );
		}

		return $info;
	}

	private function createFileBackend( array $info = [] ) {
		$dir = $info['directory'] ?? self::$mockRepoTraitDir;
		$name = $info['name'] ?? 'test';

		$info += [
			"domainId" => "mywiki",
			'name' => $info['backend'] ?? 'local-backend',
			'basePath' => $dir,
			'obResetFunc' => static function () {
				ob_end_flush();
			},
			'containerPaths' => [
				"$name-public" => "$dir",
				"$name-thumb" => "$dir/thumb",
				"$name-transcoded" => "$dir/transcoded",
				"$name-deleted" => "$dir/deleted",
				"$name-temp" => "$dir/temp",
			]
		];

		$overrides = $info['overrides'] ?? [];
		unset( $info['overrides'] );

		if ( !$overrides ) {
			return new FSFileBackend( $info );
		}

		$backend = $this->getMockBuilder( FSFileBackend::class )
			->setConstructorArgs( [ $info ] )
			->onlyMethods( array_keys( $overrides ) )
			->getMock();

		foreach ( $overrides as $name => $will ) {
			if ( is_callable( $will ) ) {
				$backend->method( $name )->willReturnCallback( $will );
			} else {
				$backend->method( $name )->willReturn( $will );
			}
		}

		return $backend;
	}

	private function importDirToTestRepo( string $dir ) {
		foreach ( new \DirectoryIterator( $dir ) as $name ) {
			$path = "$dir/$name";
			if ( is_file( $path ) ) {
				$this->importFileToTestRepo( $path );
			}
		}
	}

	private function importFileToTestRepo( string $path, ?string $destName = null ) {
		$repo = self::getTestRepo();

		$destName ??= pathinfo( $path, PATHINFO_BASENAME );

		$title = Title::makeTitleSafe( NS_FILE, $destName );
		$name = $title->getDBkey();

		$file = $repo->newFile( $name );
		$status = $file->upload( $path, 'test import', 'test image' );

		if ( !$status->isOK() ) {
			Assert::fail( "Error recording file $name: " . $status->getWikiText() );
		}

		return $file;
	}

}
