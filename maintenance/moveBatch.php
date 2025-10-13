<?php
/**
 * Move a batch of pages.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Tim Starling
 *
 *
 * This will print out error codes from Title::moveTo() if something goes wrong,
 * e.g. immobile_namespace for namespaces which can't be moved
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$this->addArg(
			'listfile',
			'List of pages to move (newline delimited) in the format <existing page name>|<new name>',
			false
		);
	}

	public function execute() {
		# Options processing
		$username = $this->getOption( 'u', false );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );
		$noRedirects = $this->hasOption( 'noredirects' );
		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if ( !$file ) {
			$this->fatalError( "Unable to read file, exiting" );
		}
		if ( $username === false ) {
			$user = User::newSystemUser( 'Move page script', [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $username );
		}
		if ( !$user || !$user->isRegistered() ) {
			$this->fatalError( "Invalid username" );
		}
		StubGlobalUser::setUser( $user );

		$movePageFactory = $this->getServiceContainer()->getMovePageFactory();

		# Setup complete, now start
		for ( $lineNum = 1; !feof( $file ); $lineNum++ ) {
			$line = fgets( $file );
			if ( $line === false ) {
				break;
			}
			$parts = array_map( 'trim', explode( '|', $line ) );
			if ( count( $parts ) !== 2 ) {
				$this->error( "Error on line $lineNum, no pipe character" );
				continue;
			}
			$source = Title::newFromText( $parts[0] );
			$dest = Title::newFromText( $parts[1] );
			if ( $source === null || $dest === null ) {
				$this->error( "Invalid title on line $lineNum" );
				continue;
			}

			$this->output( $source->getPrefixedText() . ' --> ' . $dest->getPrefixedText() );
			$this->beginTransactionRound( __METHOD__ );
			$mp = $movePageFactory->newMovePage( $source, $dest );
			$status = $mp->move( $user, $reason, !$noRedirects );
			if ( !$status->isOK() ) {
				$this->output( " FAILED\n" );
				$this->error( $status );
			}
			$this->commitTransactionRound( __METHOD__ );
			$this->output( "\n" );

			if ( $interval ) {
				sleep( $interval );
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = MoveBatch::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
