<?php
/**
 * Check that database usernames are actually valid.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to check that database usernames are actually valid.
 *
 * An existing usernames can become invalid if UserNameUtils::isValid()
 * is altered or if we change the $wgMaxNameChars
 *
 * @ingroup Maintenance
 */
class CheckUsernames extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Verify that database usernames are actually valid' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbr = $this->getReplicaDB();
		$userNameUtils = $this->getServiceContainer()->getUserNameUtils();

		$maxUserId = 0;
		do {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'user_id', 'user_name' ] )
				->from( 'user' )
				->where( $dbr->expr( 'user_id', '>', $maxUserId ) )
				->orderBy( 'user_id' )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $res as $row ) {
				if ( !$userNameUtils->isValid( $row->user_name ) ) {
					$this->output( sprintf( "Found: %6d: '%s'\n", $row->user_id, $row->user_name ) );
					wfDebugLog( 'checkUsernames', $row->user_name );
				}
			}
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $res has at at least one item
			$maxUserId = $row->user_id;
		} while ( $res->numRows() );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CheckUsernames::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
