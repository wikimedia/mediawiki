<?php
/**
 * Test for fileop performance.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\FileBackend\FileBackend;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to test fileop performance.
 *
 * @ingroup Maintenance
 */
class FileOpPerfTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Test fileop performance' );
		$this->addOption( 'b1', 'Backend 1', true, true );
		$this->addOption( 'b2', 'Backend 2', false, true );
		$this->addOption( 'srcdir', 'File source directory', true, true );
		$this->addOption( 'maxfiles', 'Max files', false, true );
		$this->addOption( 'quick', 'Avoid operation pre-checks (use doQuickOperations())' );
		$this->addOption( 'parallelize', '"parallelize" flag for doOperations()', false, true );
	}

	public function execute() {
		$backendGroup = $this->getServiceContainer()->getFileBackendGroup();
		$backend = $backendGroup->get( $this->getOption( 'b1' ) );
		$this->doPerfTest( $backend );

		if ( $this->getOption( 'b2' ) ) {
			$backend = $backendGroup->get( $this->getOption( 'b2' ) );
			$this->doPerfTest( $backend );
		}
	}

	protected function doPerfTest( FileBackend $backend ) {
		$ops1 = [];
		$ops2 = [];
		$ops3 = [];
		$ops4 = [];
		$ops5 = [];

		$baseDir = 'mwstore://' . $backend->getName() . '/testing-cont1';
		$backend->prepare( [ 'dir' => $baseDir ] );

		$dirname = $this->getOption( 'srcdir' );
		$dir = opendir( $dirname );
		if ( !$dir ) {
			return;
		}

		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		while ( ( $file = readdir( $dir ) ) !== false ) {
			if ( $file[0] != '.' ) {
				$this->output( "Using '$dirname/$file' in operations.\n" );
				$dst = $baseDir . '/' . wfBaseName( $file );
				$ops1[] = [ 'op' => 'store',
					'src' => "$dirname/$file", 'dst' => $dst, 'overwrite' => true ];
				$ops2[] = [ 'op' => 'copy',
					'src' => "$dst", 'dst' => "$dst-1", 'overwrite' => true ];
				$ops3[] = [ 'op' => 'move',
					'src' => $dst, 'dst' => "$dst-2", 'overwrite' => true ];
				$ops4[] = [ 'op' => 'delete', 'src' => "$dst-1" ];
				$ops5[] = [ 'op' => 'delete', 'src' => "$dst-2" ];
			}
			if ( count( $ops1 ) >= $this->getOption( 'maxfiles', 20 ) ) {
				break;
			}
		}
		closedir( $dir );
		$this->output( "\n" );

		$method = $this->hasOption( 'quick' ) ? 'doQuickOperations' : 'doOperations';

		$opts = [ 'force' => 1 ];
		if ( $this->hasOption( 'parallelize' ) ) {
			$opts['parallelize'] = ( $this->getOption( 'parallelize' ) === 'true' );
		}

		$start = microtime( true );
		$status = $backend->$method( $ops1, $opts );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( !$status->isGood() ) {
			$this->error( $status );
			return;
		}
		$this->output( $backend->getName() . ": Stored " . count( $ops1 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$status = $backend->$method( $ops2, $opts );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( !$status->isGood() ) {
			$this->error( $status );
			return;
		}
		$this->output( $backend->getName() . ": Copied " . count( $ops2 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$status = $backend->$method( $ops3, $opts );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( !$status->isGood() ) {
			$this->error( $status );
			return;
		}
		$this->output( $backend->getName() . ": Moved " . count( $ops3 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$status = $backend->$method( $ops4, $opts );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( !$status->isGood() ) {
			$this->error( $status );
			return;
		}
		$this->output( $backend->getName() . ": Deleted " . count( $ops4 ) . " files in $e ms.\n" );

		$start = microtime( true );
		$status = $backend->$method( $ops5, $opts );
		$e = ( microtime( true ) - $start ) * 1000;
		if ( !$status->isGood() ) {
			$this->error( $status );
			return;
		}
		$this->output( $backend->getName() . ": Deleted " . count( $ops5 ) . " files in $e ms.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = FileOpPerfTest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
