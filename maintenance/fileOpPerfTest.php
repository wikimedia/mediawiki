<?php
/**
 * Test for fileop performance.
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

$initialTime = microtime( true );
$wgProfiler = array( 'class' => 'ProfilerSimpleText' );
error_reporting( E_ALL );

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to test fileop performance.
 *
 * @ingroup Maintenance
 */
class TestFileOpPerformance extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Test fileop performance";
		$this->addOption( 'b1', 'Backend 1', true, true );
		$this->addOption( 'b2', 'Backend 2', false, true );
		$this->addOption( 'srcdir', 'File source directory', true, true );
		$this->addOption( 'maxfiles', 'Max files', false, true );
		$this->addOption( 'quick', 'Avoid operation pre-checks' );
	}

	public function execute() {
		$backend = FileBackendGroup::singleton()->get( $this->getOption( 'b1' ) );
		$this->doPerfTest( $backend );

		if ( $this->getOption( 'b2' ) ) {
			$backend = FileBackendGroup::singleton()->get( $this->getOption( 'b2' ) );
			$this->doPerfTest( $backend );
		}

		$profiler = Profiler::instance();
		$profiler->setTemplated( true );
		$profiler->logData(); // prints
	}

	protected function doPerfTest( FileBackend $backend ) {
		$ops1 = array();
		$ops2 = array();
		$ops3 = array();
		$ops4 = array();
		$ops5 = array();

		$baseDir = 'mwstore://' . $backend->getName() . '/testing-cont1';
		$backend->prepare( array( 'dir' => $baseDir ) );

		$dirname = $this->getOption( 'srcdir' );
		$dir = opendir( $dirname );
		if ( !$dir ) {
			return;
		}

		while ( $dir && ( $file = readdir( $dir ) ) !== false ) {
			if ( $file[0] != '.' ) {
				$this->output( "Using '$dirname/$file' in operations.\n" );
				$dst = $baseDir . '/' . wfBaseName( $file );
				$ops1[] = array( 'op' => 'store',
					'src' => "$dirname/$file", 'dst' => $dst, 'overwrite' => 1);
				$ops2[] = array( 'op' => 'copy',
					'src' => "$dst", 'dst' => "$dst-1", 'overwrite' => 1 );
				$ops3[] = array( 'op' => 'move',
					'src' => $dst, 'dst' => "$dst-2", 'overwrite' => 1 );
				$ops4[] = array( 'op' => 'delete', 'src' => "$dst-1" );
				$ops5[] = array( 'op' => 'delete', 'src' => "$dst-2" );
			}
			if ( count( $ops1 ) >= $this->getOption( 'maxfiles', 20 ) ) {
				break; // enough
			}
		}
		closedir( $dir );
		$this->output( "\n" );

		$method = $this->hasOption( 'quick' ) ? 'doQuickOperations' : 'doOperations';

		$start = microtime( true );
		$status = $backend->$method( $ops1, array( 'force' => 1 ) );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( $status->getErrorsArray() ) {
			print_r( $status->getErrorsArray() );
			exit(0);
		}
		$this->output( $backend->getName() . ": Stored " . count( $ops1 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$backend->$method( $ops2, array( 'force' => 1 ) );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( $status->getErrorsArray() ) {
			print_r( $status->getErrorsArray() );
			exit(0);
		}
		$this->output( $backend->getName() . ": Copied " . count( $ops2 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$backend->$method( $ops3, array( 'force' => 1 ) );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( $status->getErrorsArray() ) {
			print_r( $status->getErrorsArray() );
			exit(0);
		}
		$this->output( $backend->getName() . ": Moved " . count( $ops3 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$backend->$method( $ops4, array( 'force' => 1 ) );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( $status->getErrorsArray() ) {
			print_r( $status->getErrorsArray() );
			exit(0);
		}
		$this->output( $backend->getName() . ": Deleted " . count( $ops4 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$backend->$method( $ops5, array( 'force' => 1 ) );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( $status->getErrorsArray() ) {
			print_r( $status->getErrorsArray() );
			exit(0);
		}
		$this->output( $backend->getName() . ": Deleted " . count( $ops5 ) . " files in $e ms.\n" );
	}
}

$maintClass = "TestFileOpPerformance";
require_once( RUN_MAINTENANCE_IF_MAIN );
