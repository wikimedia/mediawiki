<?php
/**
 * File backend registration handling.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace MediaWiki\FileBackend;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\StreamFile;
use MediaWiki\Status\Status;
use Profiler;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FileBackendMultiWrite;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Class to handle file backend registration
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FileBackendGroup {
	/**
	 * @var array[] (name => ('class' => string, 'config' => array, 'instance' => object))
	 * @phan-var array<string,array{class:class-string,config:array,instance:object}>
	 */
	protected $backends = [];

	/** @var ServiceOptions */
	private $options;

	/** @var BagOStuff */
	private $srvCache;

	/** @var WANObjectCache */
	private $wanCache;

	/** @var MimeAnalyzer */
	private $mimeAnalyzer;

	/** @var LockManagerGroupFactory */
	private $lmgFactory;

	/** @var TempFSFileFactory */
	private $tmpFileFactory;

	/** @var ObjectFactory */
	private $objectFactory;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DirectoryMode,
		MainConfigNames::FileBackends,
		MainConfigNames::ForeignFileRepos,
		MainConfigNames::LocalFileRepo,
		'fallbackWikiId',
	];

	/**
	 * @param ServiceOptions $options
	 * @param ReadOnlyMode $readOnlyMode
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wanCache
	 * @param MimeAnalyzer $mimeAnalyzer
	 * @param LockManagerGroupFactory $lmgFactory
	 * @param TempFSFileFactory $tmpFileFactory
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct(
		ServiceOptions $options,
		ReadOnlyMode $readOnlyMode,
		BagOStuff $srvCache,
		WANObjectCache $wanCache,
		MimeAnalyzer $mimeAnalyzer,
		LockManagerGroupFactory $lmgFactory,
		TempFSFileFactory $tmpFileFactory,
		ObjectFactory $objectFactory
	) {
		$this->options = $options;
		$this->srvCache = $srvCache;
		$this->wanCache = $wanCache;
		$this->mimeAnalyzer = $mimeAnalyzer;
		$this->lmgFactory = $lmgFactory;
		$this->tmpFileFactory = $tmpFileFactory;
		$this->objectFactory = $objectFactory;

		// Register explicitly defined backends
		$this->register( $options->get( MainConfigNames::FileBackends ), $readOnlyMode->getConfiguredReason() );

		$autoBackends = [];
		// Automatically create b/c backends for file repos...
		$repos = array_merge(
			$options->get( MainConfigNames::ForeignFileRepos ), [ $options->get( MainConfigNames::LocalFileRepo ) ] );
		foreach ( $repos as $info ) {
			$backendName = $info['backend'];
			if ( is_object( $backendName ) || isset( $this->backends[$backendName] ) ) {
				continue; // already defined (or set to the object for some reason)
			}
			$repoName = $info['name'];
			// Local vars that used to be FSRepo members...
			$directory = $info['directory'];
			// file deletion is disabled not set
			$deletedDir = $info['deletedDir'] ?? false;
			$thumbDir = $info['thumbDir'] ?? "{$directory}/thumb";
			$transcodedDir = $info['transcodedDir'] ?? "{$directory}/transcoded";
			$lockManager = $info['lockManager'] ?? 'fsLockManager';
			// Get the FS backend configuration
			$autoBackends[] = [
				'name' => $backendName,
				'class' => FSFileBackend::class,
				'lockManager' => $lockManager,
				'containerPaths' => [
					"{$repoName}-public" => "{$directory}",
					"{$repoName}-thumb" => $thumbDir,
					"{$repoName}-transcoded" => $transcodedDir,
					"{$repoName}-deleted" => $deletedDir,
					"{$repoName}-temp" => "{$directory}/temp"
				],
				'fileMode' => $info['fileMode'] ?? 0644,
				'directoryMode' => $options->get( MainConfigNames::DirectoryMode ),
			];
		}

		// Register implicitly defined backends
		$this->register( $autoBackends, $readOnlyMode->getConfiguredReason() );
	}

	/**
	 * Register an array of file backend configurations
	 *
	 * @param array[] $configs
	 * @param string|null $readOnlyReason
	 */
	protected function register( array $configs, $readOnlyReason = null ) {
		foreach ( $configs as $config ) {
			if ( !isset( $config['name'] ) ) {
				throw new InvalidArgumentException( "Cannot register a backend with no name." );
			}
			$name = $config['name'];
			if ( isset( $this->backends[$name] ) ) {
				throw new LogicException( "Backend with name '$name' already registered." );
			} elseif ( !isset( $config['class'] ) ) {
				throw new InvalidArgumentException( "Backend with name '$name' has no class." );
			}
			$class = $config['class'];

			$config['domainId'] ??= $config['wikiId'] ?? $this->options->get( 'fallbackWikiId' );
			$config['readOnly'] ??= $readOnlyReason;

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
	 */
	public function get( $name ) {
		// Lazy-load the actual backend instance
		if ( !isset( $this->backends[$name]['instance'] ) ) {
			$config = $this->config( $name );

			$class = $config['class'];
			// Checking old alias for compatibility with unchanged config
			if ( $class === FileBackendMultiWrite::class || $class === \FileBackendMultiWrite::class ) {
				// @todo How can we test this? What's the intended use-case?
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
	 */
	public function config( $name ) {
		if ( !isset( $this->backends[$name] ) ) {
			throw new InvalidArgumentException( "No backend defined with the name '$name'." );
		}

		$config = $this->backends[$name]['config'];

		return array_merge(
			// Default backend parameters
			[
				'mimeCallback' => $this->guessMimeInternal( ... ),
				'obResetFunc' => 'wfResetOutputBuffers',
				'asyncHandler' => DeferredUpdates::addCallableUpdate( ... ),
				'streamMimeFunc' => StreamFile::contentTypeFromPath( ... ),
				'tmpFileFactory' => $this->tmpFileFactory,
				'statusWrapper' => Status::wrap( ... ),
				'wanCache' => $this->wanCache,
				'srvCache' => $this->srvCache,
				'logger' => LoggerFactory::getInstance( 'FileOperation' ),
				'profiler' => static function ( $section ) {
					return Profiler::instance()->scopedProfileIn( $section );
				}
			],
			// Configured backend parameters
			$config,
			// Resolved backend parameters
			[
				'class' => $this->backends[$name]['class'],
				'lockManager' =>
					$this->lmgFactory->getLockManagerGroup( $config['domainId'] )
						->get( $config['lockManager'] ),
			]
		);
	}

	/**
	 * Get an appropriate backend object from a storage path
	 *
	 * @param string $storagePath
	 * @return FileBackend|null Backend or null on failure
	 */
	public function backendFromPath( $storagePath ) {
		[ $backend, , ] = FileBackend::splitStoragePath( $storagePath );
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
		// Trust the extension of the storage path (caller must validate)
		$ext = FileBackend::extensionFromPath( $storagePath );
		$type = $this->mimeAnalyzer->getMimeTypeFromExtensionOrNull( $ext );
		// For files without a valid extension (or one at all), inspect the contents
		if ( !$type && $fsPath ) {
			$type = $this->mimeAnalyzer->guessMimeType( $fsPath, false );
		} elseif ( !$type && $content !== null && $content !== '' ) {
			$tmpFile = $this->tmpFileFactory->newTempFSFile( 'mime_', '' );
			file_put_contents( $tmpFile->getPath(), $content );
			$type = $this->mimeAnalyzer->guessMimeType( $tmpFile->getPath(), false );
		}
		return $type ?: 'unknown/unknown';
	}
}
/** @deprecated class alias since 1.43 */
class_alias( FileBackendGroup::class, 'FileBackendGroup' );
