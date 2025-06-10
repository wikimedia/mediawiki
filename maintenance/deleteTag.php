<?php

/**
 * Remove a revision tag from edits and log entries it was applied to.
 * @see bug T75181
 */

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Storage\NameTableAccessException;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class DeleteTag extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Deletes a change tag' );
		$this->addArg( 'tag name', 'Name of the tag to delete' );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$dbw = $this->getPrimaryDB();
		$services = $this->getServiceContainer();
		$defStore = $services->getChangeTagDefStore();

		$tag = $this->getArg( 0 );
		try {
			$tagId = $defStore->getId( $tag );
		} catch ( NameTableAccessException ) {
			$this->fatalError( "Tag '$tag' not found" );
		}

		$status = ChangeTags::canDeleteTag( $tag, null, ChangeTags::BYPASS_MAX_USAGE_CHECK );
		if ( !$status->isOK() ) {
			$this->fatalError( $status );
		}

		$this->output( "Deleting tag '$tag'...\n" );

		// Make the tag impossible to add by users while we're deleting it and drop the
		// usage counter to zero
		$dbw->newUpdateQueryBuilder()
			->update( 'change_tag_def' )
			->set( [
				'ctd_user_defined' => 0,
				'ctd_count' => 0,
			] )
			->where( [ 'ctd_id' => $tagId ] )
			->caller( __METHOD__ )->execute();
		$this->getServiceContainer()->getChangeTagsStore()->purgeTagCacheAll();

		// Iterate over change_tag, deleting rows in batches
		$count = 0;
		do {
			$ids = $dbw->newSelectQueryBuilder()
				->select( 'ct_id' )
				->from( 'change_tag' )
				->where( [ 'ct_tag_id' => $tagId ] )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchFieldValues();

			if ( !$ids ) {
				break;
			}
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'change_tag' )
				->where( [ 'ct_id' => $ids ] )
				->caller( __METHOD__ )->execute();
			$count += $dbw->affectedRows();
			$this->output( "$count\n" );
			$this->waitForReplication();
		} while ( true );
		$this->output( "The tag has been removed from $count revisions, deleting the tag itself...\n" );

		$this->getServiceContainer()->getChangeTagsStore()->deleteTagEverywhere( $tag );
		$this->output( "Done.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteTag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
