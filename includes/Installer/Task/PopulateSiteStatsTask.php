<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * Insert an initial row into the site_stats table
 *
 * @internal For use by the installer
 */
class PopulateSiteStatsTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'stats';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'tables';
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_TABLES );
		if ( !$status->isOK() ) {
			return $status;
		}
		$status->getDB()->newInsertQueryBuilder()
			->insertInto( 'site_stats' )
			->ignore()
			->row( [
				'ss_row_id' => 1,
				'ss_total_edits' => 0,
				'ss_good_articles' => 0,
				'ss_total_pages' => 0,
				'ss_users' => 0,
				'ss_active_users' => 0,
				'ss_images' => 0,
			] )
			->caller( __METHOD__ )
			->execute();

		return Status::newGood();
	}
}
