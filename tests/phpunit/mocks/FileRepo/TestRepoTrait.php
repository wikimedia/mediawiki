<?php

namespace MediaWiki\Tests\FileRepo;

use InvalidArgumentException;
use LogicException;
use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use PHPUnit\Framework\Assert;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FSFileBackend;

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
			throw new InvalidArgumentException( "Not in temp dir: $dir" );
		}

		$name = basename( $dir );
		if ( !str_starts_with( $name, 'mw-mock-repo-' ) ) {
			throw new InvalidArgumentException( "Not a mock repo dir: $dir" );
		}

		// TODO: Recursively delete the directory. Scary!

		self::$mockRepoTraitDir = null;
	}

	private function installTestRepoGroup( array $options = [] ) {
		$repoGroup = $this->createTestRepoGroup( $options );
		$this->setService( 'RepoGroup', $repoGroup );

		$this->installTestBackendGroup( $repoGroup->getLocalRepo()->getBackend() );
	}

	private function createTestRepoGroup( array $options = [], ?MediaWikiServices $services = null ): RepoGroup {
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

	private function installTestBackendGroup( FileBackend $backend ) {
		$this->setService( 'FileBackendGroup', $this->createTestBackendGroup( $backend ) );
	}

	/** @return FileBackend */
	private function createTestBackendGroup( FileBackend $backend ) {
		$expected = "mwstore://{$backend->getName()}/";

		$backendGroup = $this->createNoOpMock( FileBackendGroup::class, [ 'backendFromPath' ] );
		$backendGroup->method( 'backendFromPath' )->willReturnCallback(
			static function ( $path ) use ( $expected, $backend ) {
				if ( str_starts_with( $path, $expected ) ) {
					return $backend;
				}

				return null;
			}
		);

		return $backendGroup;
	}

	private function getLocalFileRepoConfig( array $options = [] ): array {
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
			"class" => LocalRepo::class,
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
			"backend" => 'local-backend',
		];

		if ( !$info['backend'] instanceof FileBackend ) {
			$info['backend'] = $this->createFileBackend( $info );
		}

		return $info;
	}

	/** @return FileBackend */
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
			'headerFunc' => function ( string $header ) {
				$this->recordHeader( $header );
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

	private function importFileToTestRepo( string $path, ?string $destName = null ): File {
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

	private function copyFileToTestBackend( string $src, string $dst ) {
		$repo = self::getTestRepo();
		$backend = $repo->getBackend();

		$zone = strstr( ltrim( $dst, '/' ), '/', true );
		$name = basename( $dst );

		$dstFile = $repo->newFile( $name );
		$dst = $dstFile->getRel();

		if ( $zone !== null ) {
			$zonePath = $repo->getZonePath( $zone );

			if ( $zonePath ) {
				$dst = "$zonePath/$dst";
			}
		}

		$dir = dirname( $dst );

		if ( $dir !== '' ) {
			$status = $backend->prepare(
				[ 'op' => 'prepare', 'dir' => $dir ]
			);

			if ( !$status->isOK() ) {
				Assert::fail( "Error copying file $src to $dst: " . $status );
			}
		}

		$status = $backend->store(
			[ 'op' => 'store', 'src' => $src, 'dst' => $dst, ],
		);

		if ( !$status->isOK() ) {
			Assert::fail( "Error copying file $src to $dst: " . $status );
		}
	}

	private function recordHeader( string $header ) {
		// no-op
	}

}
