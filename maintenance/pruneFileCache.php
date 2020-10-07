<?php
/**
 * Prune file cache for pages, objects, resources, etc.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that prunes file cache for pages, objects, resources, etc.
 *
 * @ingroup Maintenance
 */
class PruneFileCache extends Maintenance {

	protected $minSurviveTimestamp;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete file cache files older than "agedays"' );
		$this->addOption( 'agedays', 'How many days old files must be in order to delete', true, true );
		$this->addOption( 'subdir', 'Prune one $wgFileCacheDirectory subdirectory name', false, true );
	}

	public function execute() {
		global $wgUseFileCache, $wgFileCacheDirectory;

		if ( !$wgUseFileCache ) {
			$this->fatalError( "Nothing to do -- \$wgUseFileCache is disabled." );
		}

		$age = $this->getOption( 'agedays' );
		if ( !ctype_digit( $age ) ) {
			$this->fatalError( "Non-integer 'age' parameter given." );
		}
		// Delete items with a TS older than this
		$this->minSurviveTimestamp = time() - ( 86400 * $age );

		$dir = $wgFileCacheDirectory;
		if ( !is_dir( $dir ) ) {
			$this->fatalError( "Nothing to do -- \$wgFileCacheDirectory directory not found." );
		}

		$subDir = $this->getOption( 'subdir' );
		if ( $subDir !== null ) {
			if ( !is_dir( "$dir/$subDir" ) ) {
				$this->fatalError( "The specified subdirectory `$subDir` does not exist." );
			}
			$this->output( "Pruning `$dir/$subDir` directory...\n" );
			$this->prune_directory( "$dir/$subDir", 'report' );
			$this->output( "Done pruning `$dir/$subDir` directory\n" );
		} else {
			$this->output( "Pruning `$dir` directory...\n" );
			// Note: don't prune things like .cdb files on the top level!
			$this->prune_directory( $dir, 'report' );
			$this->output( "Done pruning `$dir` directory\n" );
		}
	}

	/**
	 * @param string $dir
	 * @param string|bool $report Use 'report' to report the directories being scanned
	 */
	protected function prune_directory( $dir, $report = false ) {
		$tsNow = time();
		$dirHandle = opendir( $dir );
		while ( ( $file = readdir( $dirHandle ) ) !== false ) {
			// Skip ".", "..", and also any dirs or files like ".svn" or ".htaccess"
			if ( $file[0] != "." ) {
				// absolute
				$path = $dir . '/' . $file;
				if ( is_dir( $path ) ) {
					if ( $report === 'report' ) {
						$this->output( "Scanning `$path`...\n" );
					}
					$this->prune_directory( $path );
				} else {
					$mts = filemtime( $path );
					// Sanity check the file extension against known cache types
					if ( $mts < $this->minSurviveTimestamp
						&& preg_match( '/\.(?:html|cache)(?:\.gz)?$/', $file )
						&& unlink( $path )
					) {
						$daysOld = round( ( $tsNow - $mts ) / 86400, 2 );
						$this->output( "Deleted `$path` [days=$daysOld]\n" );
					}
				}
			}
		}
		closedir( $dirHandle );
	}
}

$maintClass = PruneFileCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
