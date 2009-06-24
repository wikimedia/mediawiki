<?php
/**
 * Maintenance script to move a batch of pages
 *
 * @ingroup Maintenance
 * @author Tim Starling
 *
 * USAGE: php moveBatch.php [-u <user>] [-r <reason>] [-i <interval>] [listfile]
 *
 * [listfile] - file with two titles per line, separated with pipe characters;
 * the first title is the source, the second is the destination.
 * Standard input is used if listfile is not given.
 * <user> - username to perform moves as
 * <reason> - reason to be given for moves
 * <interval> - number of seconds to sleep after each move
 *
 * This will print out error codes from Title::moveTo() if something goes wrong,
 * e.g. immobile_namespace for namespaces which can't be moved
 */

require_once( "Maintenance.php" );

class MoveBatch extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Moves a batch of pages";
		$this->addParam( 'u', "User to perform move", false, true );
		$this->addParam( 'r', "Reason to move page", false, true );
		$this->addParam( 'i', "Interval to sleep between moves" );
		$this->addArgs( array( 'listfile' ) );
	}
	
	public function execute() {
		global $wgUser;

		# Change to current working directory
		$oldCwd = getcwd();
		chdir( $oldCwd );

		# Options processing
		$user = $this->getOption( 'u', 'Move page script' );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );
		if( $this->hasArg() ) {
			$file = fopen( $this->getArg(), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if( !$file ) {
			$this->error( "Unable to read file, exiting\n", true );
		}
		$wgUser = User::newFromName( $user );
		
		# Setup complete, now start
		$dbw = wfGetDB( DB_MASTER );
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = fgets( $file );
			if ( $line === false ) {
				break;
			}
			$parts = array_map( 'trim', explode( '|', $line ) );
			if ( count( $parts ) != 2 ) {
				$this->error( "Error on line $linenum, no pipe character\n" );
				continue;
			}
			$source = Title::newFromText( $parts[0] );
			$dest = Title::newFromText( $parts[1] );
			if ( is_null( $source ) || is_null( $dest ) ) {
				$this->error( "Invalid title on line $linenum\n" );
				continue;
			}
	
	
			$this->output( $source->getPrefixedText() . ' --> ' . $dest->getPrefixedText() );
			$dbw->begin();
			$err = $source->moveTo( $dest, false, $reason );
			if( $err !== true ) {
				$this->output( "\nFAILED: $err" );
			}
			$dbw->immediateCommit();
			$this->output( "\n" );
	
			if ( $interval ) {
				sleep( $interval );
			}
			wfWaitForSlaves( 5 );
		}
	}
}

$maintClass = "MoveBatch";
require_once( DO_MAINTENANCE );
