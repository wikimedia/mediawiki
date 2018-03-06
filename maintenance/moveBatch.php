<?php
/**
 * Move a batch of pages.
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
 * @author Tim Starling
 *
 * USAGE: php moveBatch.php [-u <user>] [-r <reason>] [-i <interval>] [-noredirects] [listfile]
 *
 * [listfile] - file with two titles per line, separated with pipe characters;
 * the first title is the source, the second is the destination.
 * Standard input is used if listfile is not given.
 * <user> - username to perform moves as
 * <reason> - reason to be given for moves
 * <interval> - number of seconds to sleep after each move
 * <noredirects> - suppress creation of redirects
 *
 * This will print out error codes from Title::moveTo() if something goes wrong,
 * e.g. immobile_namespace for namespaces which can't be moved
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to move a batch of pages.
 *
 * @ingroup Maintenance
 */
class MoveBatch extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Moves a batch of pages' );
		$this->addOption( 'u', "User to perform move", false, true );
		$this->addOption( 'r', "Reason to move page", false, true );
		$this->addOption( 'i', "Interval to sleep between moves" );
		$this->addOption( 'noredirects', "Suppress creation of redirects" );
		$this->addArg( 'listfile', 'List of pages to move, newline delimited', false );
	}

	public function execute() {
		global $wgUser;

		# Change to current working directory
		$oldCwd = getcwd();
		chdir( $oldCwd );

		# Options processing
		$user = $this->getOption( 'u', false );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );
		$noredirects = $this->hasOption( 'noredirects' );
		if ( $this->hasArg() ) {
			$file = fopen( $this->getArg(), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if ( !$file ) {
			$this->fatalError( "Unable to read file, exiting" );
		}
		if ( $user === false ) {
			$wgUser = User::newSystemUser( 'Move page script', [ 'steal' => true ] );
		} else {
			$wgUser = User::newFromName( $user );
		}
		if ( !$wgUser ) {
			$this->fatalError( "Invalid username" );
		}

		# Setup complete, now start
		$dbw = $this->getDB( DB_MASTER );
		// phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = fgets( $file );
			if ( $line === false ) {
				break;
			}
			$parts = array_map( 'trim', explode( '|', $line ) );
			if ( count( $parts ) != 2 ) {
				$this->error( "Error on line $linenum, no pipe character" );
				continue;
			}
			$source = Title::newFromText( $parts[0] );
			$dest = Title::newFromText( $parts[1] );
			if ( is_null( $source ) || is_null( $dest ) ) {
				$this->error( "Invalid title on line $linenum" );
				continue;
			}

			$this->output( $source->getPrefixedText() . ' --> ' . $dest->getPrefixedText() );
			$this->beginTransaction( $dbw, __METHOD__ );
			$mp = new MovePage( $source, $dest );
			$status = $mp->move( $wgUser, $reason, !$noredirects );
			if ( !$status->isOK() ) {
				$this->output( "\nFAILED: " . $status->getWikiText( false, false, 'en' ) );
			}
			$this->commitTransaction( $dbw, __METHOD__ );
			$this->output( "\n" );

			if ( $interval ) {
				sleep( $interval );
			}
		}
	}
}

$maintClass = MoveBatch::class;
require_once RUN_MAINTENANCE_IF_MAIN;
