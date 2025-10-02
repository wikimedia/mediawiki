<?php
/**
 * Undelete a page by fetching it from the archive table
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class Undelete extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Undelete a page' );
		$this->addOption( 'user', 'The user to perform the undeletion', false, true, 'u' );
		$this->addOption( 'reason', 'The reason to undelete', false, true, 'r' );
		$this->addArg( 'pagename', 'Page to undelete' );
	}

	public function execute() {
		$username = $this->getOption( 'user', false );
		$reason = $this->getOption( 'reason', '' );
		$pageName = $this->getArg( 0 );

		$title = Title::newFromText( $pageName );
		if ( !$title ) {
			$this->fatalError( "Invalid title" );
		}
		if ( $username === false ) {
			$user = User::newSystemUser( 'Command line script', [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $username );
		}
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->output( "Undeleting " . $title->getPrefixedDBkey() . "...\n" );

		$this->beginTransactionRound( __METHOD__ );
		$status = $this->getServiceContainer()->getUndeletePageFactory()
			->newUndeletePage( $page, $user )
			->undeleteUnsafe( $reason );
		$this->commitTransactionRound( __METHOD__ );

		if ( !$status->isGood() ) {
			$this->fatalError( $status );
		}
		$this->output( "done\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = Undelete::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
