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

require_once __DIR__ . '/../Maintenance.php';

/**
 * Manage foreign resources registered with ResourceLoader.
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class ManageForeignResources extends Maintenance {
	private $defaultAlgo = 'sha384';
	private $tmpParentDir;

	public function __construct() {
		global $IP;
		parent::__construct();
		$this->addDescription( <<<TEXT
Manage foreign resources registered with ResourceLoader.

This helps developers to download, verify and update local copies of upstream
libraries registered as ResourceLoader modules. See also foreign-resources.yaml.

For sources that don't publish an integrity hash, leave the value empty at
first, and run this script with --make-sri to compute the hashes.

This script runs in dry mode by default. Use --update to actually change, remove,
or add files to /resources/lib/.
TEXT
		);
		$this->addArg( 'module', 'Name of a single module (Default: all)', false );
		$this->addOption( 'update', ' resources/lib/ missing integrity metadata' );
		$this->addOption( 'make-sri', 'Compute missing integrity metadata' );
		$this->addOption( 'verbose', 'Be verbose' );

		// Use a directory in $IP instead of wfTempDir() because
		// PHP's rename() does not work across file systems.
		$this->tmpParentDir = "{$IP}/resources/tmp";
	}

	public function execute() {
		global $IP;
		$module = $this->getArg();
		$makeSRI = $this->hasOption( 'make-sri' );

		$registry = $this->parseBasicYaml(
			file_get_contents( __DIR__ . '/foreign-resources.yaml' )
		);
		foreach ( $registry as $moduleName => $info ) {
			if ( $module !== null && $moduleName !== $module ) {
				continue;
			}
			$this->verbose( "\n### {$moduleName}\n\n" );

			// Validate required keys
			$info += [ 'src' => null, 'integrity' => null, 'dest' => null ];
			if ( $info['src'] === null ) {
				$this->fatalError( "Module '$moduleName' must have a 'src' key." );
			}
			$integrity = is_string( $info['integrity'] ) ? $info['integrity'] : $makeSRI;
			if ( $integrity === false ) {
				$this->fatalError( "Module '$moduleName' must have an 'integrity' key." );
			}

			// Download the resource
			$data = Http::get( $info['src'], [ 'followRedirects' => false ] );
			if ( $data === false ) {
				$this->fatalError( "Failed to download resource for '$moduleName'." );
			}

			// Validate integrity metadata
			$this->output( "... checking integrity of '{$moduleName}'\n" );
			$algo = $integrity === true ? $this->defaultAlgo : explode( '-', $integrity )[0];
			$actualIntegrity = $algo . '-' . base64_encode( hash( $algo, $data, true ) );
			if ( $integrity === true ) {
				$this->output( "Integrity for '{$moduleName}':\n\t${actualIntegrity}\n" );
				continue;
			} elseif ( $integrity !== $actualIntegrity ) {
				$this->fatalError( "Integrity check failed for '{$moduleName}:\n" .
					"Expected: {$integrity}\n" .
					"Actual: {$actualIntegrity}"
				);
			}

			// Determine destination
			$destDir = "{$IP}/resources/lib/$moduleName";
			$this->output( "... extracting files for '{$moduleName}'\n" );
			$this->handleTypeTar( $moduleName, $data, $destDir, $info );
		}

		// Clean up
		wfRecursiveRemoveDir( $this->tmpParentDir );
		$this->output( "\nDone!\n" );
	}

	private function handleTypeTar( $moduleName, $data, $destDir, array $info ) {
		global $IP;
		wfRecursiveRemoveDir( $this->tmpParentDir );
		if ( !wfMkdirParents( $this->tmpParentDir ) ) {
			$this->fatalError( "Unable to create {$this->tmpParentDir}" );
		}

		// Write resource to temporary file and open it
		$tmpFile = "{$this->tmpParentDir}/$moduleName.tar";
		$this->verbose( "... writing '$moduleName' src to $tmpFile\n" );
		file_put_contents( $tmpFile, $data );
		$p = new PharData( $tmpFile );
		$tmpDir = "{$this->tmpParentDir}/$moduleName";
		$p->extractTo( $tmpDir );
		unset( $data, $p );

		if ( $info['dest'] === null ) {
			// Replace the entire directory as-is
			if ( !$this->hasOption( 'update' ) ) {
				$this->output( "[dry run] Would replace /resources/lib/$moduleName\n" );
			} else {
				wfRecursiveRemoveDir( $destDir );
				if ( !rename( $tmpDir, $destDir ) ) {
					$this->fatalError( "Could not move $destDir to $tmpDir." );
				}
			}
			return;
		}

		// Create and/or empty the destination
		if ( !$this->hasOption( 'update' ) ) {
			$this->output( "... [dry run] would empty /resources/lib/$moduleName\n" );
		} else {
			wfRecursiveRemoveDir( $destDir );
			wfMkdirParents( $destDir );
		}

		// Expand and normalise the 'dest' entries
		$toCopy = [];
		foreach ( $info['dest'] as $fromSubPath => $toSubPath ) {
			// Use glob() to expand wildcards and check existence
			$fromPaths = glob( "{$tmpDir}/{$fromSubPath}", GLOB_BRACE );
			if ( !$fromPaths ) {
				$this->fatalError( "Path '$fromSubPath' of '$moduleName' not found." );
			}
			foreach ( $fromPaths as $fromPath ) {
				$toCopy[$fromPath] = $toSubPath === null
					? "$destDir/" . basename( $fromPath )
					: "$destDir/$toSubPath/" . basename( $fromPath );
			}
		}
		foreach ( $toCopy as $from => $to ) {
			if ( !$this->hasOption( 'update' ) ) {
				$shortFrom = strtr( $from, [ "$tmpDir/" => '' ] );
				$shortTo = strtr( $to, [ "$IP/" => '' ] );
				$this->output( "... [dry run] would move $shortFrom to $shortTo\n" );
			} else {
				$this->verbose( "... moving $from to $to\n" );
				wfMkdirParents( dirname( $to ) );
				if ( !rename( $from, $to ) ) {
					$this->fatalError( "Could not move $from to $to." );
				}
			}
		}
	}

	private function verbose( $text ) {
		if ( $this->hasOption( 'verbose' ) ) {
			$this->output( $text );
		}
	}

	/**
	 * Basic YAML parser.
	 *
	 * Supports only string or object values, and 2 spaces indentation.
	 *
	 * @todo Just ship symfony/yaml.
	 * @param string $input
	 * @return array
	 */
	private function parseBasicYaml( $input ) {
		$lines = explode( "\n", $input );
		$root = [];
		$stack = [ &$root ];
		$prev = 0;
		foreach ( $lines as $i => $text ) {
			$line = $i + 1;
			$trimmed = ltrim( $text, ' ' );
			if ( $trimmed === '' || $trimmed[0] === '#' ) {
				continue;
			}
			$indent = strlen( $text ) - strlen( $trimmed );
			if ( $indent % 2 !== 0 ) {
				throw new Exception( __METHOD__ . ": Odd indentation on line $line." );
			}
			$depth = $indent === 0 ? 0 : ( $indent / 2 );
			if ( $depth < $prev ) {
				// Close previous branches we can't re-enter
				array_splice( $stack, $depth + 1 );
			}
			if ( !array_key_exists( $depth, $stack ) ) {
				throw new Exception( __METHOD__ . ": Too much indentation on line $line." );
			}
			if ( strpos( $trimmed, ':' ) === false ) {
				throw new Exception( __METHOD__ . ": Missing colon on line $line." );
			}
			$dest =& $stack[ $depth ];
			if ( $dest === null ) {
				// Promote from null to object
				$dest = [];
			}
			list( $key, $val ) = explode( ':', $trimmed, 2 );
			$val = ltrim( $val, ' ' );
			if ( $val !== '' ) {
				// Add string
				$dest[ $key ] = $val;
			} else {
				// Add null (may become an object later)
				$val = null;
				$stack[] = &$val;
				$dest[ $key ] = &$val;
			}
			$prev = $depth;
			unset( $dest, $val );
		}
		return $root;
	}
}

$maintClass = ManageForeignResources::class;
require_once RUN_MAINTENANCE_IF_MAIN;
