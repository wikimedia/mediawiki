<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

use Composer\Spdx\SpdxLicenses;
use LogicException;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Manage foreign resources registered with ResourceLoader.
 *
 * @since 1.32
 * @ingroup ResourceLoader
 * @see https://www.mediawiki.org/wiki/Foreign_resources
 */
class ForeignResourceManager {
	private string $defaultAlgo = 'sha384';

	private bool $hasErrors = false;

	private string $tmpParentDir;

	private string $cacheDir;

	/**
	 * @var callable|Closure
	 * @phan-var callable(string):void
	 */
	private $infoPrinter;

	/**
	 * @var callable|Closure
	 * @phan-var callable(string):void
	 */
	private $errorPrinter;
	/**
	 * @var callable|Closure
	 * @phan-var callable(string):void
	 */
	private $verbosePrinter;

	/** @var string */
	private $action;

	/** @var array[] */
	private $registry;

	private GlobalIdGenerator $globalIdGenerator;

	/**
	 * @param string $registryFile Path to YAML file
	 * @param string $libDir Path to a modules directory
	 * @param callable|null $infoPrinter Callback for printing info about the run.
	 * @param callable|null $errorPrinter Callback for printing errors from the run.
	 * @param callable|null $verbosePrinter Callback for printing extra verbose
	 *  progress information from the run.
	 */
	public function __construct(
		private readonly string $registryFile,
		private readonly string $libDir,
		?callable $infoPrinter = null,
		?callable $errorPrinter = null,
		?callable $verbosePrinter = null
	) {
		$this->globalIdGenerator = MediaWikiServices::getInstance()->getGlobalIdGenerator();
		$this->infoPrinter = $infoPrinter ?? static function ( $_ ) {
		};
		$this->errorPrinter = $errorPrinter ?? $this->infoPrinter;
		$this->verbosePrinter = $verbosePrinter ?? static function ( $_ ) {
		};

		// Support XDG_CACHE_HOME to speed up CI by avoiding repeated downloads.
		$cacheHome = getenv( 'XDG_CACHE_HOME' );
		if ( $cacheHome !== false ) {
			$this->cacheDir = realpath( $cacheHome ) . '/mw-foreign';
		} else {
			$conf = MediaWikiServices::getInstance()->getMainConfig();
			$cacheConf = $conf->get( MainConfigNames::CacheDirectory );
			if ( $cacheConf !== false ) {
				$this->cacheDir = "$cacheConf/ForeignResourceManager";
			} else {
				$this->cacheDir = "{$this->libDir}/.foreign/cache";
			}
		}
	}

	/**
	 * @throws LogicException
	 */
	public function run( string $action, string $module ): bool {
		$actions = [ 'update', 'verify', 'make-sri', 'make-cdx' ];
		if ( !in_array( $action, $actions ) ) {
			$this->error( "Invalid action.\n\nMust be one of " . implode( ', ', $actions ) . '.' );
			return false;
		}
		$this->action = $action;
		$this->setupTempDir( $action );

		$this->registry = Yaml::parseFile( $this->registryFile );
		if ( $module === 'all' ) {
			$modules = $this->registry;
		} elseif ( isset( $this->registry[$module] ) ) {
			$modules = [ $module => $this->registry[$module] ];
		} else {
			$this->error( "Unknown module name.\n\nMust be one of:\n" .
				wordwrap( implode( ', ', array_keys( $this->registry ) ), 80 ) .
				'.'
			);
			return false;
		}

		if ( $this->action === 'make-cdx' ) {
			$cdxJson = FormatJson::encode(
				$this->generateCdxForModules( $modules ),
				"\t",
				FormatJson::UTF8_OK
			);

			if ( $cdxJson === false ) {
				$this->error( 'json_encode() returned false.' );
				return false;
			}

			$cdxFile = $this->getCdxFileLocation();
			file_put_contents( $cdxFile, $cdxJson );
			$this->output( "Created CycloneDX file at $cdxFile\n" );
			return true;
		}

		foreach ( $modules as $moduleName => $info ) {
			$this->verbose( "\n### {$moduleName}\n\n" );

			if ( $this->action === 'update' ) {
				$this->output( "... updating '{$moduleName}'\n" );
			} elseif ( $this->action === 'verify' ) {
				$this->output( "... verifying '{$moduleName}'\n" );
			} else {
				$this->output( "... checking '{$moduleName}'\n" );
			}

			// Do checks on YAML content (such as license existence, validity and type keys)
			// before doing any potentially destructive actions (potentially deleting directories,
			// depending on the action.

			if ( !isset( $info['type'] ) ) {
				throw new LogicException( "Module '$moduleName' must have a 'type' key." );
			}

			$this->validateLicense( $moduleName, $info );

			if ( $info['type'] === 'doc-only' ) {
				$this->output( "... {$moduleName} is documentation-only, skipping integrity checks.\n" );
				continue;
			}

			$destDir = "{$this->libDir}/$moduleName";

			if ( $this->action === 'update' ) {
				$this->verbose( "... emptying directory for $moduleName\n" );
				wfRecursiveRemoveDir( $destDir );
			}

			$this->verbose( "... preparing {$this->tmpParentDir}\n" );
			wfRecursiveRemoveDir( $this->tmpParentDir );
			if ( !wfMkdirParents( $this->tmpParentDir ) ) {
				throw new LogicException( "Unable to create {$this->tmpParentDir}" );
			}

			switch ( $info['type'] ) {
				case 'tar':
				case 'zip':
					$this->handleTypeTar( $moduleName, $destDir, $info, $info['type'] );
					break;
				case 'file':
					$this->handleTypeFile( $moduleName, $destDir, $info );
					break;
				case 'multi-file':
					$this->handleTypeMultiFile( $moduleName, $destDir, $info );
					break;
				default:
					throw new LogicException( "Unknown type '{$info['type']}' for '$moduleName'" );
			}
		}

		$this->cleanUp();
		if ( $this->hasErrors ) {
			// The "verify" action should check all modules and files and fail after, not during.
			// We don't throw on the first issue so that developers enjoy access to all actionable
			// information at once (given we can't have cascading errors).
			// The "verify" action prints errors along the way and simply exits here.
			return false;
		}

		return true;
	}

	/**
	 * Returns a JSON string describing the foreign resources in a CycloneDX format.
	 */
	public function generateCdx(): string {
		$this->registry = Yaml::parseFile( $this->registryFile );
		return json_encode(
			$this->generateCdxForModules( $this->registry ),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
		);
	}

	/**
	 * Get the path to the CycloneDX file that describes the foreign resources.
	 */
	public function getCdxFileLocation(): string {
		return "$this->libDir/foreign-resources.cdx.json";
	}

	/**
	 * Choose the temp parent directory
	 */
	private function setupTempDir( string $action ): void {
		if ( $action === 'verify' ) {
			$this->tmpParentDir = wfTempDir() . '/ForeignResourceManager';
		} else {
			// Use a temporary directory under the destination directory instead
			// of wfTempDir() because PHP's rename() does not work across file
			// systems, and the user's /tmp and $IP may be on different filesystems.
			$this->tmpParentDir = "{$this->libDir}/.foreign/tmp";
		}
	}

	private function cacheKey( string $src, string $integrity, string $moduleName ): string {
		$key = $moduleName
			. '_' . hash( 'fnv132', $integrity )
			. '_' . hash( 'fnv132', $src )
			// Append a readable filename to aid cache inspection and debugging
			. '_' . basename( $src );
		$key = preg_replace( '/[.\/+?=_-]+/', '_', $key );
		return rtrim( $key, '_' );
	}

	private function cacheGet( string $key ): string|false {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		return @file_get_contents( "{$this->cacheDir}/$key.data" );
	}

	private function cacheSet( string $key, mixed $data ): void {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		@mkdir( $this->cacheDir, 0o777, true );
		file_put_contents( "{$this->cacheDir}/$key.data", $data, LOCK_EX );
	}

	private function fetch( string $src, ?string $integrity, string $moduleName ): string {
		if ( $integrity !== null ) {
			$key = $this->cacheKey( $src, $integrity, $moduleName );
			$data = $this->cacheGet( $key );
			if ( $data ) {
				return $data;
			}
		}

		$services = MediaWikiServices::getInstance();
		$req = $services->getHttpRequestFactory()
			->create( $src, [ 'method' => 'GET', 'followRedirects' => false ], __METHOD__ );
		$reqStatusValue = $req->execute();
		if ( !$reqStatusValue->isOK() ) {
			$message = "Failed to download resource at {$src}";
			$reqError = $reqStatusValue->getMessages( 'error' )[0] ?? null;
			if ( $reqError !== null ) {
				$message .= ': ' . Message::newFromSpecifier( $reqError )->inLanguage( 'en' )->plain();
			}
			throw new ForeignResourceNetworkException( $message );
		}
		if ( $req->getStatus() !== 200 ) {
			throw new ForeignResourceNetworkException( "Unexpected HTTP {$req->getStatus()} response from {$src}" );
		}
		$data = $req->getContent();
		$algo = $integrity === null ? $this->defaultAlgo : explode( '-', $integrity )[0];
		$actualIntegrity = $algo . '-' . base64_encode( hash( $algo, $data, true ) );
		if ( $integrity === $actualIntegrity ) {
			$this->verbose( "... passed integrity check for {$src}\n" );
			$key = $this->cacheKey( $src, $actualIntegrity, $moduleName );
			$this->cacheSet( $key, $data );
		} elseif ( $this->action === 'make-sri' ) {
			$this->output( "Integrity for {$src}\n\tintegrity: {$actualIntegrity}\n" );
		} else {
			$expectedIntegrity = $integrity ?? 'null';
			throw new ForeignResourceNetworkException( "Integrity check failed for {$src}\n" .
				"\tExpected: {$expectedIntegrity}\n" .
				"\tActual: {$actualIntegrity}"
			);
		}
		return $data;
	}

	private function handleTypeFile( string $moduleName, string $destDir, array $info ): void {
		if ( !isset( $info['src'] ) ) {
			throw new LogicException( "Module '$moduleName' must have a 'src' key." );
		}
		$data = $this->fetch( $info['src'], $info['integrity'] ?? null, $moduleName );
		$dest = $info['dest'] ?? basename( $info['src'] );
		$path = "$destDir/$dest";
		if ( $this->action === 'verify' && sha1_file( $path ) !== sha1( $data ) ) {
			$this->error( "File for '$moduleName' is different.\n" );
		}
		if ( $this->action === 'update' ) {
			wfMkdirParents( $destDir );
			file_put_contents( "$destDir/$dest", $data );
		}
	}

	private function handleTypeMultiFile( string $moduleName, string $destDir, array $info ): void {
		if ( !isset( $info['files'] ) ) {
			throw new LogicException( "Module '$moduleName' must have a 'files' key." );
		}
		foreach ( $info['files'] as $dest => $file ) {
			if ( !isset( $file['src'] ) ) {
				throw new LogicException( "Module '$moduleName' file '$dest' must have a 'src' key." );
			}
			$data = $this->fetch( $file['src'], $file['integrity'] ?? null, $moduleName );
			$path = "$destDir/$dest";
			if ( $this->action === 'verify' && sha1_file( $path ) !== sha1( $data ) ) {
				$this->error( "File '$dest' for '$moduleName' is different.\n" );
			} elseif ( $this->action === 'update' ) {
				wfMkdirParents( $destDir );
				file_put_contents( "$destDir/$dest", $data );
			}
		}
	}

	private function handleTypeTar( string $moduleName, string $destDir, array $info, string $fileType ): void {
		$info += [ 'src' => null, 'integrity' => null, 'dest' => null ];
		if ( $info['src'] === null ) {
			throw new LogicException( "Module '$moduleName' must have a 'src' key." );
		}
		// Download the resource to a temporary file and open it
		$data = $this->fetch( $info['src'], $info['integrity'], $moduleName );
		$tmpFile = "{$this->tmpParentDir}/$moduleName." . $fileType;
		$this->verbose( "... writing '$moduleName' src to $tmpFile\n" );
		file_put_contents( $tmpFile, $data );
		$p = new PharData( $tmpFile );
		$tmpDir = "{$this->tmpParentDir}/$moduleName";
		$p->extractTo( $tmpDir );
		unset( $data, $p );

		if ( $info['dest'] === null ) {
			// Default: Replace the entire directory
			$toCopy = [ $tmpDir => $destDir ];
		} else {
			// Expand and normalise the 'dest' entries
			$toCopy = [];
			foreach ( $info['dest'] as $fromSubPath => $toSubPath ) {
				// Use glob() to expand wildcards and check existence
				$fromPaths = glob( "{$tmpDir}/{$fromSubPath}", GLOB_BRACE );
				if ( !$fromPaths ) {
					throw new LogicException( "Path '$fromSubPath' of '$moduleName' not found." );
				}
				foreach ( $fromPaths as $fromPath ) {
					$toCopy[$fromPath] = $toSubPath === null
						? "$destDir/" . basename( $fromPath )
						: "$destDir/$toSubPath/" . basename( $fromPath );
				}
			}
		}
		foreach ( $toCopy as $from => $to ) {
			if ( $this->action === 'verify' ) {
				$this->verbose( "... verifying $to\n" );
				if ( is_dir( $from ) ) {
					$rii = new RecursiveIteratorIterator( new RecursiveDirectoryIterator(
						$from,
						RecursiveDirectoryIterator::SKIP_DOTS
					) );
					/** @var SplFileInfo $file */
					foreach ( $rii as $file ) {
						$remote = $file->getPathname();
						$local = strtr( $remote, [ $from => $to ] );
						if ( sha1_file( $remote ) !== sha1_file( $local ) ) {
							$this->error( "File '$local' is different.\n" );
						}
					}
				} elseif ( sha1_file( $from ) !== sha1_file( $to ) ) {
					$this->error( "File '$to' is different.\n" );
				}
			} elseif ( $this->action === 'update' ) {
				$this->verbose( "... moving $from to $to\n" );
				wfMkdirParents( dirname( $to ) );
				if ( !rename( $from, $to ) ) {
					throw new LogicException( "Could not move $from to $to." );
				}
			}
		}
	}

	private function verbose( string $text ): void {
		( $this->verbosePrinter )( $text );
	}

	private function output( string $text ): void {
		( $this->infoPrinter )( $text );
	}

	private function error( string $text ): void {
		$this->hasErrors = true;
		( $this->errorPrinter )( $text );
	}

	private function cleanUp(): void {
		wfRecursiveRemoveDir( $this->tmpParentDir );

		// Prune the cache of files we don't recognise.
		$knownKeys = [];
		foreach ( $this->registry as $module => $info ) {
			if ( $info['type'] === 'file' || $info['type'] === 'tar' ) {
				$knownKeys[] = $this->cacheKey( $info['src'], $info['integrity'], $module );
			} elseif ( $info['type'] === 'multi-file' ) {
				foreach ( $info['files'] as $file ) {
					$knownKeys[] = $this->cacheKey( $file['src'], $file['integrity'], $module );
				}
			}
		}
		foreach ( glob( "{$this->cacheDir}/*" ) as $cacheFile ) {
			if ( !in_array( basename( $cacheFile, '.data' ), $knownKeys ) ) {
				unlink( $cacheFile );
			}
		}
	}

	private function validateLicense( string $moduleName, array $info ): void {
		if ( !isset( $info['license'] ) || !is_string( $info['license'] ) ) {
			throw new LogicException(
				"Module '$moduleName' needs a valid SPDX license; no license is currently present"
			);
		}
		$licenses = new SpdxLicenses();
		if ( !$licenses->validate( $info['license'] ) ) {
			$this->error(
				"Module '$moduleName' has an invalid SPDX license identifier '{$info['license']}', "
				. "see <https://spdx.org/licenses/>.\n"
			);
		}
	}

	private function generateCdxForModules( array $modules ): array {
		$cdx = [
			'$schema' => 'http://cyclonedx.org/schema/bom-1.6.schema.json',
			'bomFormat' => 'CycloneDX',
			'specVersion' => '1.6',
			'serialNumber' => 'urn:uuid:' . $this->globalIdGenerator->newUUIDv4(),
			'version' => 1,
			'components' => [],
		];
		foreach ( $modules as $moduleName => $module ) {
			$moduleCdx = [
				'type' => 'library',
				'name' => $moduleName,
				'version' => $module['version'],
			];
			if ( preg_match( '/ (AND|OR|WITH) /', $module['license'] ) ) {
				$moduleCdx['licenses'][] = [ 'expression' => $module['license'] ];
			} else {
				$moduleCdx['licenses'][] = [ 'license' => [ 'id' => $module['license'] ] ];
			}
			if ( $module['purl'] ?? false ) {
				$moduleCdx['purl'] = $module['purl'];
			}
			if ( $module['version'] ?? false ) {
				$moduleCdx['version'] = $module['version'];
			}
			if ( $module['authors'] ?? false ) {
				$moduleCdx['authors'] = array_map(
					static fn ( $author ) => [ 'name' => $author ],
					preg_split( '/,( and)? /', $module['authors'] )
				);
			}
			if ( $module['homepage'] ?? false ) {
				$moduleCdx['externalReferences'] = [ [ 'url' => $module['homepage'], 'type' => 'website' ] ];
			}
			$cdx['components'][] = $moduleCdx;
		}
		return $cdx;
	}
}
