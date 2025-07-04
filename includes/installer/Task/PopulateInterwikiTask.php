<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;
use Wikimedia\AtEase\AtEase;

/**
 * Populate the interwiki table from maintenance/interwiki.list
 *
 * @internal For use by the installer
 */
class PopulateInterwikiTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'interwiki';
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
		$conn = $status->getDB();

		$row = $conn->newSelectQueryBuilder()
			->select( '1' )
			->from( 'interwiki' )
			->caller( __METHOD__ )->fetchRow();
		if ( $row ) {
			$status->warning( 'config-install-interwiki-exists' );
			return $status;
		}

		AtEase::suppressWarnings();
		$rows = file( MW_INSTALL_PATH . '/maintenance/interwiki.list',
			FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		AtEase::restoreWarnings();
		if ( !$rows ) {
			return Status::newFatal( 'config-install-interwiki-list' );
		}
		$insert = $conn->newInsertQueryBuilder()
			->insertInto( 'interwiki' );
		foreach ( $rows as $row ) {
			$row = preg_replace( '/^\s*([^#]*?)\s*(#.*)?$/', '\\1', $row ); // strip comments - whee
			if ( $row == "" ) {
				continue;
			}
			$row .= "|";
			$insert->row(
				array_combine(
					[ 'iw_prefix', 'iw_url', 'iw_local', 'iw_api', 'iw_wikiid' ],
					explode( '|', $row )
				)
			);
		}
		$insert->caller( __METHOD__ )->execute();

		return Status::newGood();
	}
}
