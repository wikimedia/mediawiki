<?php

/**
 * Remove a revision tag from edits and log entries it was applied to.
 * @see bug T75181
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableAccessException;

require_once __DIR__ . '/Maintenance.php';

class DeleteTag extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Deletes a change tag' );
		$this->addArg( 'tag name', 'Name of the tag to delete' );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$dbw = $this->getDB( DB_PRIMARY );
		$services = MediaWikiServices::getInstance();
		$defStore = $services->getChangeTagDefStore();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$options = [ 'domain' => $lbFactory->getLocalDomainID() ];

		$tag = $this->getArg( 0 );
		try {
			$tagId = $defStore->getId( $tag );
		} catch ( NameTableAccessException $ex ) {
			$this->fatalError( "Tag '$tag' not found" );
		}

		$status = ChangeTags::canDeleteTag( $tag, null, ChangeTags::BYPASS_MAX_USAGE_CHECK );
		if ( !$status->isOK() ) {
			$message = $status->getHTML( false, false, 'en' );
			$this->fatalError( Sanitizer::stripAllTags( $message ) );
		}

		$this->output( "Deleting tag '$tag'...\n" );

		// Make the tag impossible to add by users while we're deleting it and drop the
		// usage counter to zero
		$dbw->update(
			'change_tag_def',
			[
				'ctd_user_defined' => 0,
				'ctd_count' => 0,
			],
			[ 'ctd_id' => $tagId ],
			__METHOD__
		);
		ChangeTags::purgeTagCacheAll();

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
			$dbw->delete( 'change_tag', [ 'ct_id' => $ids ], __METHOD__ );
			$count += $dbw->affectedRows();
			$this->output( "$count\n" );
			$lbFactory->waitForReplication( $options );
		} while ( true );
		$this->output( "The tag has been removed from $count revisions, deleting the tag itself...\n" );

		ChangeTags::deleteTagEverywhere( $tag );
		$this->output( "Done.\n" );
	}
}

$maintClass = DeleteTag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
