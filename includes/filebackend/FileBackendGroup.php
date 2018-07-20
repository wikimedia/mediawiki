<?php
/**
 * File backend registration handling.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup FileBackend
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Class to handle file backend registration
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FileBackendGroup {
	/** @var FileBackendGroup */
	protected static $instance = null;

	/** @var array (name => ('class' => string, 'config' => array, 'instance' => object)) */
	protected $backends = [];

	protected function __construct() {
	}

	/**
	 * @return FileBackendGroup
	 */
	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
			self::$instance->initFromGlobals();
		}

		return self::$instance;
	}

	/**
	 * Destroy the singleton instance
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Register file backends from the global variables
	 */
	protected function initFromGlobals() {
		global $wgLocalFileRepo, $wgForeignFileRepos, $wgFileBackends, $wgDirectoryMode;

		// Register explicitly defined backends
		$this->register( $wgFileBackends, wfConfiguredReadOnlyReason() );

		$autoBackends = [];
		// Automatically create b/c backends for file repos...
		$repos = array_merge( $wgForeignFileRepos, [ $wgLocalFileRepo ] );
		foreach ( $repos as $info ) {
			$backendName = $info['backend'];
			if ( is_object( $backendName ) || isset( $this->backends[$backendName] ) ) {
				continue; // already defined (or set to the object for some reason)
			}
			$repoName = $info['name'];
			// Local vars that used to be FSRepo members...
			$directory = $info['directory'];
			$deletedDir = $info['deletedDir'] ?? false; // deletion disabled
			$thumbDir = $info['thumbDir'] ?? "{$directory}/thumb";
			$transcodedDir = $info['transcodedDir'] ?? "{$directory}/transcoded";
			// Get the FS backend configuration
			$autoBackends[] = [
				'name' => $backendName,
				'class' => FSFileBackend::class,
				'lockManager' => 'fsLockManager',
				'containerPaths' => [
					"{$repoName}-public" => "{$directory}",
					"{$repoName}-thumb" => $thumbDir,
					"{$repoName}-transcoded" => $transcodedDir,
					"{$repoName}-deleted" => $deletedDir,
					"{$repoName}-temp" => "{$directory}/temp"
				],
				'fileMode' => $info['fileMode'] ?? 0644,
				'directoryMode' => $wgDirectoryMode,
			];
		}

		// Register implicitly defined backends
		$this->register( $autoBackends, wfConfiguredReadOnlyReason() );
	}

	/**
	 * Register an array of file backend configurations
	 *
	 * @param array[] $configs
	 * @param string|null $readOnlyReason
	 * @throws InvalidArgumentException
	 */
	protected function register( array $configs, $readOnlyReason = null ) {
		foreach ( $configs as $config ) {
			if ( !isset( $config['name'] ) ) {
				throw new InvalidArgumentException( "Cannot register a backend with no name." );
			}
			$name = $config['name'];
			if ( isset( $this->backends[$name] ) ) {
				throw new LogicException( "Backend with name `{$name}` already registered." );
			} elseif ( !isset( $config['class'] ) ) {
				throw new InvalidArgumentException( "Backend with name `{$name}` has no class." );
			}
			$class = $config['class'];

			$config['readOnly'] = $config['readOnly'] ?? $readOnlyReason;

			unset( $config['class'] ); // backend won't need this
			$this->backends[$name] = [
				'class' => $class,
				'config' => $config,
				'instance' => null
			];
		}
	}

	/**
	 * Get the backend object with a given name
	 *
	 * @param string $name
	 * @return FileBackend
	 * @throws InvalidArgumentException
	 */
	public function get( $name ) {
		// Lazy-load the actual backend instance
		if ( !isset( $this->backends[$name]['instance'] ) ) {
			$config = $this->config( $name );

			$class = $config['class'];
			if ( $class === FileBackendMultiWrite::class ) {
				foreach ( $config['backends'] as $index => $beConfig ) {
					if ( isset( $beConfig['template'] ) ) {
						// Config is just a modified version of a registered backend's.
						// This should only be used when that config is used only by this backend.
						$config['backends'][$index] += $this->config( $beConfig['template'] );
					}
				}
			}

			$this->backends[$name]['instance'] = new $class( $config );
		}

		return $this->backends[$name]['instance'];
	}

	/**
	 * Get the config array for a backend object with a given name
	 *
	 * @param string $name
	 * @return array Parameters to FileBackend::__construct()
	 * @throws InvalidArgumentException
	 */
	public function config( $name ) {
		if ( !isset( $this->backends[$name] ) ) {
			throw new InvalidArgumentException( "No backend defined with the name `$name`." );
		}
		$class = $this->backends[$name]['class'];

		$config = $this->backends[$name]['config'];
		$config['class'] = $class;
		$config += [ // set defaults
			'wikiId' => wfWikiID(), // e.g. "my_wiki-en_"
			'mimeCallback' => [ $this, 'guessMimeInternal' ],
			'obResetFunc' => 'wfResetOutputBuffers',
			'streamMimeFunc' => [ StreamFile::class, 'contentTypeFromPath' ],
			'tmpDirectory' => wfTempDir(),
			'statusWrapper' => [ Status::class, 'wrap' ],
			'wanCache' => MediaWikiServices::getInstance()->getMainWANObjectCache(),
			'srvCache' => ObjectCache::getLocalServerInstance( 'hash' ),
			'logger' => LoggerFactory::getInstance( 'FileOperation' ),
			'profiler' => Profiler::instance()
		];
		$config['lockManager'] =
			LockManagerGroup::singleton( $config['wikiId'] )->get( $config['lockManager'] );
		$config['fileJournal'] = isset( $config['fileJournal'] )
			? FileJournal::factory( $config['fileJournal'], $name )
			: FileJournal::factory( [ 'class' => NullFileJournal::class ], $name );

		return $config;
	}

	/**
	 * Get an appropriate backend object from a storage path
	 *
	 * @param string $storagePath
	 * @return FileBackend|null Backend or null on failure
	 */
	public function backendFromPath( $storagePath ) {
		list( $backend, , ) = FileBackend::splitStoragePath( $storagePath );
		if ( $backend !== null && isset( $this->backends[$backend] ) ) {
			return $this->get( $backend );
		}

		return null;
	}

	/**
	 * @param string $storagePath
	 * @param string|null $content
	 * @param string|null $fsPath
	 * @return string
	 * @since 1.27
	 */
	public function guessMimeInternal( $storagePath, $content, $fsPath ) {
		$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
		// Trust the extension of the storage path (caller must validate)
		$ext = FileBackend::extensionFromPath( $storagePath );
		$type = $magic->guessTypesForExtension( $ext );
		// For files without a valid extension (or one at all), inspect the contents
		if ( !$type && $fsPath ) {
			$type = $magic->guessMimeType( $fsPath, false );
		} elseif ( !$type && strlen( $content ) ) {
			$tmpFile = TempFSFile::factory( 'mime_', '', wfTempDir() );
			file_put_contents( $tmpFile->getPath(), $content );
			$type = $magic->guessMimeType( $tmpFile->getPath(), false );
		}
		return $type ?: 'unknown/unknown';
	}
}
