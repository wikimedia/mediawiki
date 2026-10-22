<?php

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Maintenance\LoggedUpdateOutcome;

require_once __DIR__ . '/TableCleanup.php';

/**
 * @ingroup Maintenance
 */
class CleanupWatchlistLabelMember extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Add watchlist labels to talk pages (to match those of the subject pages).' );
		$this->setBatchSize( 500 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates(): LoggedUpdateOutcome {
		$dbw = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase();
		$batchNum = 1;
		while ( true ) {
			// Find the missing talk page data for the watchlist_label_member table.
			$missingLabelMembers = $dbw->newSelectQueryBuilder()
				->select( [ 'wlm_subject.wlm_label', 'wl_talk.wl_id', 'wl_talk.wl_namespace' ] )
				->distinct()
				// Select the watchlist
				->from( 'watchlist', 'wl_subject' )
				// Join to the watchlist talk pages (where NS IDs are incremented by one)
				->join( 'watchlist', 'wl_talk', [
					'wl_subject.wl_user = wl_talk.wl_user',
					'wl_subject.wl_namespace + 1 = wl_talk.wl_namespace',
					'wl_subject.wl_title = wl_talk.wl_title',
					// Making sure it's not the same row.
					'wl_subject.wl_id != wl_talk.wl_id',
				] )
				// Determine if the watchlist item (for the subject page) is labelled.
				->join( 'watchlist_label_member', 'wlm_subject', [
					'wlm_subject.wlm_item = wl_subject.wl_id',
				] )
				// And find the labels for the matching talk page (if any).
				->leftJoin( 'watchlist_label_member', 'wlm_talk', [
					'wlm_talk.wlm_item = wl_talk.wl_id',
					'wlm_talk.wlm_label = wlm_subject.wlm_label',
				] )
				->where( [
					// Exclude the matched talk page rows if they exist.
					'wlm_talk.wlm_item IS NULL',
					// Ensure that the subject and talk namespaces are valid.
					'wl_subject.wl_namespace % 2 = 0',
				] )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchResultSet();
			$data = [];
			foreach ( $missingLabelMembers as $missingLabelMember ) {
				$data[] = [
					'wlm_label' => $missingLabelMember->wlm_label,
					'wlm_item' => $missingLabelMember->wl_id,
				];
			}
			if ( !$data ) {
				$this->output( $batchNum === 1 ? "Nothing to fix.\n" : "Done.\n" );
				return LoggedUpdateOutcome::COMPLETE;
			}
			$this->output(
				'Fixing labels on talk pages for ' . count( $data ) . ' watchlist items (batch ' . $batchNum . ").\n"
			);
			$batchNum++;
			$dbw->newInsertQueryBuilder()
				->insertInto( 'watchlist_label_member' )
				->rows( $data )
				->caller( __METHOD__ )
				->execute();
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupWatchlistLabelMember::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
