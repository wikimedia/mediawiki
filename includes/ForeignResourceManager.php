<?php
/**
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
 * @ingroup Maintenance
 */

use MediaWiki\MediaWikiServices;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\AtEase\AtEase;

/**
 * Manage foreign resources registered with ResourceLoader.
 *
 * @since 1.32
 */
class ForeignResourceManager {
	/** @var string */
	private $defaultAlgo = 'sha384';

	/** @var bool */
	private $hasErrors = false;

	/** @var string */
	private $registryFile;

	/** @var string */
	private $libDir;

	/** @var string */
	private $tmpParentDir;

	/** @var string */
	private $cacheDir;

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

	/**
	 * @param string $registryFile Path to YAML file
	 * @param string $libDir Path to a modules directory
	 * @param callable|null $infoPrinter Callback for printing info about the run.
	 * @param callable|null $errorPrinter Callback for printing errors from the run.
	 * @param callable|null $verbosePrinter Callback for printing extra verbose
	 *  progress information from the run.
	 */
	public function __construct(
		$registryFile,
		$libDir,
		callable $infoPrinter = null,
		callable $errorPrinter = null,
		callable $verbosePrinter = null
	) {
		$this->registryFile = $registryFile;
		$this->libDir = $libDir;
		$this->infoPrinter = $infoPrinter ?? static function ( $_ ) {
		};
		$this->errorPrinter = $errorPrinter ?? $this->infoPrinter;
		$this->verbosePrinter = $verbosePrinter ?? static function ( $_ ) {
		};

		// Use a temporary directory under the destination directory instead
		// of wfTempDir() because PHP's rename() does not work across file
		// systems, and the user's /tmp and $IP may be on different filesystems.
		$this->tmpParentDir = "{$this->libDir}/.foreign/tmp";

		$cacheHome = getenv( 'XDG_CACHE_HOME' ) ? realpath( getenv( 'XDG_CACHE_HOME' ) ) : false;
		$this->cacheDir = $cacheHome ? "$cacheHome/mw-foreign" : "{$this->libDir}/.foreign/cache";
	}

	/**
	 * @param string $action
	 * @param string $module
	 * @return bool
	 * @throws Exception
	 */
	public function run( $action, $module ) {
		$actions = [ 'update', 'verify', 'make-sri' ];
		if ( !in_array( $action, $actions ) ) {
			$this->error( "Invalid action.\n\nMust be one of " . implode( ', ', $actions ) . '.' );
			return false;
		}
		$this->action = $action;

		$this->registry = Yaml::parseFile( $this->registryFile );
		if ( $module === 'all' ) {
			$modules = $this->registry;
		} elseif ( isset( $this->registry[ $module ] ) ) {
			$modules = [ $module => $this->registry[ $module ] ];
		} else {
			$this->error( "Unknown module name.\n\nMust be one of:\n" .
				wordwrap( implode( ', ', array_keys( $this->registry ) ), 80 ) .
				'.'
			);
			return false;
		}

		foreach ( $modules as $moduleName => $info ) {
			$this->verbose( "\n### {$moduleName}\n\n" );
			$destDir = "{$this->libDir}/$moduleName";

			if ( $this->action === 'update' ) {
				$this->output( "... updating '{$moduleName}'\n" );
				$this->verbose( "... emptying directory for $moduleName\n" );
				wfRecursiveRemoveDir( $destDir );
			} elseif ( $this->action === 'verify' ) {
				$this->output( "... verifying '{$moduleName}'\n" );
			} else {
				$this->output( "... checking '{$moduleName}'\n" );
			}

			$this->verbose( "... preparing {$this->tmpParentDir}\n" );
			wfRecursiveRemoveDir( $this->tmpParentDir );
			if ( !wfMkdirParents( $this->tmpParentDir ) ) {
				throw new Exception( "Unable to create {$this->tmpParentDir}" );
			}

			if ( !isset( $info['type'] ) ) {
				throw new Exception( "Module '$moduleName' must have a 'type' key." );
			}
			switch ( $info['type'] ) {
				case 'tar':
					$this->handleTypeTar( $moduleName, $destDir, $info );
					break;
				case 'file':
					$this->handleTypeFile( $moduleName, $destDir, $info );
					break;
				case 'multi-file':
					$this->handleTypeMultiFile( $moduleName, $destDir, $info );
					break;
				default:
					throw new Exception( "Unknown type '{$info['type']}' for '$moduleName'" );
			}
		}

		$this->output( "\nDone!\n" );
		$this->cleanUp();
		if ( $this->hasErrors ) {
			// The verify mode should check all modules/files and fail after, not during.
			return false;
		}

		return true;
	}

	/**
	 * @param string $src
	 * @param string $integrity
	 *
	 * @return string
	 */
	private function cacheKey( $src, $integrity ) {
		$key = basename( $src ) . '_' . substr( $integrity, -12 );
		$key = preg_replace( '/[.\/+?=_-]+/', '_', $key );
		return rtrim( $key, '_' );
	}

	/**
	 * @param string $key
	 * @return string|false
	 */
	private function cacheGet( $key ) {
		return AtEase::quietCall( 'file_get_contents', "{$this->cacheDir}/$key.data" );
	}

	/**
	 * @param string $key
	 * @param mixed $data
	 */
	private function cacheSet( $key, $data ) {
		wfMkdirParents( $this->cacheDir );
		file_put_contents( "{$this->cacheDir}/$key.data", $data, LOCK_EX );
	}

	/**
	 * @param string $src
	 * @param string $integrity
	 *
	 * @return string
	 */
	private function fetch( $src, $integrity ) {
		$key = $this->cacheKey( $src, $integrity );
		$data = $this->cacheGet( $key );
		if ( $data ) {
			return $data;
		}

		$req = MediaWikiServices::getInstance()->getHttpRequestFactory()
			->create( $src, [ 'method' => 'GET', 'followRedirects' => false ], __METHOD__ );
		if ( !$req->execute()->isOK() ) {
			throw new Exception( "Failed to download resource at {$src}" );
		}
		if ( $req->getStatus() !== 200 ) {
			throw new Exception( "Unexpected HTTP {$req->getStatus()} response from {$src}" );
		}
		$data = $req->getContent();
		$algo = $integrity === null ? $this->defaultAlgo : explode( '-', $integrity )[0];
		$actualIntegrity = $algo . '-' . base64_encode( hash( $algo, $data, true ) );
		if ( $integrity === $actualIntegrity ) {
			$this->verbose( "... passed integrity check for {$src}\n" );
			$this->cacheSet( $key, $data );
		} elseif ( $this->action === 'make-sri' ) {
			$this->output( "Integrity for {$src}\n\tintegrity: {$actualIntegrity}\n" );
		} else {
			$expectedIntegrity = $integrity ?? 'null';
			throw new Exception( "Integrity check failed for {$src}\n" .
				"\tExpected: {$expectedIntegrity}\n" .
				"\tActual: {$actualIntegrity}"
			);
		}
		return $data;
	}

	/**
	 * @param string $moduleName
	 * @param string $destDir
	 * @param array $info
	 */
	private function handleTypeFile( $moduleName, $destDir, array $info ) {
		if ( !isset( $info['src'] ) ) {
			throw new Exception( "Module '$moduleName' must have a 'src' key." );
		}
		$data = $this->fetch( $info['src'], $info['integrity'] ?? null );
		$dest = $info['dest'] ?? basename( $info['src'] );
		$path = "$destDir/$dest";
		if ( $this->action === 'verify' && sha1_file( $path ) !== sha1( $data ) ) {
			throw new Exception( "File for '$moduleName' is different." );
		}
		if ( $this->action === 'update' ) {
			wfMkdirParents( $destDir );
			file_put_contents( "$destDir/$dest", $data );
		}
	}

	/**
	 * @param string $moduleName
	 * @param string $destDir
	 * @param array $info
	 */
	private function handleTypeMultiFile( $moduleName, $destDir, array $info ) {
		if ( !isset( $info['files'] ) ) {
			throw new Exception( "Module '$moduleName' must have a 'files' key." );
		}
		foreach ( $info['files'] as $dest => $file ) {
			if ( !isset( $file['src'] ) ) {
				throw new Exception( "Module '$moduleName' file '$dest' must have a 'src' key." );
			}
			$data = $this->fetch( $file['src'], $file['integrity'] ?? null );
			$path = "$destDir/$dest";
			if ( $this->action === 'verify' && sha1_file( $path ) !== sha1( $data ) ) {
				throw new Exception( "File '$dest' for '$moduleName' is different." );
			} elseif ( $this->action === 'update' ) {
				wfMkdirParents( $destDir );
				file_put_contents( "$destDir/$dest", $data );
			}
		}
	}

	/**
	 * @param string $moduleName
	 * @param string $destDir
	 * @param array $info
	 */
	private function handleTypeTar( $moduleName, $destDir, array $info ) {
		$info += [ 'src' => null, 'integrity' => null, 'dest' => null ];
		if ( $info['src'] === null ) {
			throw new Exception( "Module '$moduleName' must have a 'src' key." );
		}
		// Download the resource to a temporary file and open it
		$data = $this->fetch( $info['src'], $info['integrity' ] );
		$tmpFile = "{$this->tmpParentDir}/$moduleName.tar";
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
					throw new Exception( "Path '$fromSubPath' of '$moduleName' not found." );
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
							$this->error( "File '$local' is different." );
							$this->hasErrors = true;
						}
					}
				} elseif ( sha1_file( $from ) !== sha1_file( $to ) ) {
					$this->error( "File '$to' is different." );
					$this->hasErrors = true;
				}
			} elseif ( $this->action === 'update' ) {
				$this->verbose( "... moving $from to $to\n" );
				wfMkdirParents( dirname( $to ) );
				if ( !rename( $from, $to ) ) {
					throw new Exception( "Could not move $from to $to." );
				}
			}
		}
	}

	/**
	 * @param string $text
	 */
	private function verbose( $text ) {
		( $this->verbosePrinter )( $text );
	}

	/**
	 * @param string $text
	 */
	private function output( $text ) {
		( $this->infoPrinter )( $text );
	}

	/**
	 * @param string $text
	 */
	private function error( $text ) {
		( $this->errorPrinter )( $text );
	}

	private function cleanUp() {
		wfRecursiveRemoveDir( $this->tmpParentDir );

		// Prune the cache of files we don't recognise.
		$knownKeys = [];
		foreach ( $this->registry as $info ) {
			if ( $info['type'] === 'file' || $info['type'] === 'tar' ) {
				$knownKeys[] = $this->cacheKey( $info['src'], $info['integrity'] );
			} elseif ( $info['type'] === 'multi-file' ) {
				foreach ( $info['files'] as $file ) {
					$knownKeys[] = $this->cacheKey( $file['src'], $file['integrity'] );
				}
			}
		}
		foreach ( glob( "{$this->cacheDir}/*" ) as $cacheFile ) {
			if ( !in_array( basename( $cacheFile, '.data' ), $knownKeys ) ) {
				unlink( $cacheFile );
			}
		}
	}
}
