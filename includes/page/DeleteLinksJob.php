<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
