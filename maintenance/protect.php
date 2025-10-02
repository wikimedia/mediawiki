<?php
/**
 * Protect or unprotect a page.
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

/**
 * Maintenance script that protects or unprotects a page.
 *
 * @ingroup Maintenance
 */
class Protect extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Protect or unprotect a page from the command line.' );
		$this->addOption( 'unprotect', 'Removes protection' );
		$this->addOption( 'semiprotect', 'Adds semi-protection' );
		$this->addOption( 'cascade', 'Add cascading protection' );
		$this->addOption( 'user', 'Username to protect with', false, true, 'u' );
		$this->addOption( 'reason', 'Reason for un/protection', false, true, 'r' );
		$this->addArg( 'title', 'Title to protect', true );
	}

	public function execute() {
		$userName = $this->getOption( 'user', false );
		$reason = $this->getOption( 'reason', '' );

		$cascade = $this->hasOption( 'cascade' );

		$protection = "sysop";
		if ( $this->hasOption( 'semiprotect' ) ) {
			$protection = "autoconfirmed";
		} elseif ( $this->hasOption( 'unprotect' ) ) {
			$protection = "";
		}

		if ( $userName === false ) {
			$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $userName );
		}
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}

		$t = Title::newFromText( $this->getArg( 0 ) );
		if ( !$t ) {
			$this->fatalError( "Invalid title" );
		}

		$services = $this->getServiceContainer();
		$restrictions = [];
		foreach ( $services->getRestrictionStore()->listApplicableRestrictionTypes( $t ) as $type ) {
			$restrictions[$type] = $protection;
		}

		# un/protect the article
		$this->output( "Updating protection status..." );

		$this->beginTransactionRound( __METHOD__ );
		$page = $services->getWikiPageFactory()->newFromTitle( $t );
		$status = $page->doUpdateRestrictions( $restrictions, [], $cascade, $reason, $user );
		$this->commitTransactionRound( __METHOD__ );

		if ( $status->isOK() ) {
			$this->output( "done\n" );
		} else {
			$this->output( "failed\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = Protect::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
