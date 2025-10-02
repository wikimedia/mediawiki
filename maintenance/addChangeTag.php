<?php

/**
 * Adds a change tag to the wiki.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Adds a change tag to the wiki
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class AddChangeTag extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Adds a change tag to the wiki.' );

		$this->addOption( 'tag', 'Tag to add', true, true );
		$this->addOption( 'reason', 'Reason for adding the tag', true, true );
	}

	public function execute() {
		$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );

		$tag = $this->getOption( 'tag' );

		$status = ChangeTags::createTagWithChecks(
			$tag,
			$this->getOption( 'reason' ),
			new UltimateAuthority( $user )
		);

		if ( !$status->isGood() ) {
			$this->fatalError( $status );
		}

		$this->output( "$tag was created.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = AddChangeTag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
