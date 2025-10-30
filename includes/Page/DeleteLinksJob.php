<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Job to prune link tables for pages that were deleted
 *
 * @internal For use by core in LinksDeletionUpdate only.
 * @since 1.27
 * @ingroup Page
 */
class DeleteLinksJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'deleteLinks', $title, $params );
		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		if ( $this->title === null ) {
			$this->setLastError( "deleteLinks: Invalid title" );
			return false;
		}

		$pageId = $this->params['pageId'];

		// Serialize links updates by page ID so they see each others' changes
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$scopedLock = LinksUpdate::acquirePageLock( $dbw, $pageId, 'job' );
		if ( $scopedLock === null ) {
			$this->setLastError( 'LinksUpdate already running for this page, try again later.' );
			return false;
		}

		$services = MediaWikiServices::getInstance();
		$wikiPageFactory = $services->getWikiPageFactory();
		if ( $wikiPageFactory->newFromID( $pageId, IDBAccessObject::READ_LATEST ) ) {
			// The page was restored somehow or something went wrong
			$this->setLastError( "deleteLinks: Page #$pageId exists" );
			return false;
		}

		$dbProvider = $services->getConnectionProvider();
		$timestamp = $this->params['timestamp'] ?? null;
		$page = $wikiPageFactory->newFromTitle( $this->title ); // title when deleted

		$update = new LinksDeletionUpdate( $page, $pageId, $timestamp );
		$update->setTransactionTicket( $dbProvider->getEmptyTransactionTicket( __METHOD__ ) );
		$update->doUpdate();

		return true;
	}
}
