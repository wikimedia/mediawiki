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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Revision\SlotRecord;
use Psr\Log\LoggerInterface;

/**
 * @ingroup JobQueue
 */
class ParsoidCachePrewarmJob extends Job implements GenericParameterJob {
	private LoggerInterface $logger;
	private ParsoidOutputAccess $parsoidOutputAccess;

	public function __construct( array $params ) {
		parent::__construct( 'parsoidCachePrewarm', $params );

		$this->parsoidOutputAccess = MediaWikiServices::getInstance()->getParsoidOutputAccess();
		$this->logger = LoggerFactory::getInstance( 'ParsoidCachePrewarmJob' );
	}

	public static function newSpec(
		int $revisionId,
		int $pageId
	): JobSpecification {
		return new JobSpecification(
			'parsoidCachePrewarm',
			[
				'revId' => $revisionId,
				'pageId' => $pageId
			]
		);
	}

	private function doParsoidCacheUpdate() {
		$services = MediaWikiServices::getInstance();
		$page = $services->getPageStore()->getPageById( $this->params['pageId'] );
		$revId = $this->params['revId'];

		if ( $page === null ) {
			// This happens when the page got deleted in the meantime.
			$this->logger->info( "Page with ID {$this->params['pageId']} not found" );
			return;
		}

		if ( $page->getLatest() !== $revId ) {
			$this->logger->info(
				'ParsoidCachePrewarmJob: The ID of the new revision does not match the page\'s current revision ID'
			);
			return;
		}

		$rev = $services->getRevisionLookup()->getRevisionById( $revId );
		if ( !$rev ) {
			return;
		}

		$parserOpts = ParserOptions::newFromAnon();

		$mainSlot = $rev->getSlot( SlotRecord::MAIN );
		if ( !$this->parsoidOutputAccess->supportsContentModel( $mainSlot->getModel() ) ) {
			$this->logger->debug( __METHOD__ . ': Parsoid does not support content model ' . $mainSlot->getModel() );
			return;
		}

		$this->logger->debug( __METHOD__ . ': generating Parsoid output' );

		// getParserOutput() will write to ParserCache
		$status = $this->parsoidOutputAccess->getParserOutput(
			$page,
			$parserOpts,
			$rev,
			ParsoidOutputAccess::OPT_FORCE_PARSE
			| ParsoidOutputAccess::OPT_LOG_LINT_DATA
		);

		if ( !$status->isOK() ) {
			$this->logger->error( __METHOD__ . ': Parsoid error', [
				'errors' => $status->getErrors(),
				'page' => $page->getDBkey(),
				'rev' => $rev->getId(),
			] );
		}
	}

	public function run() {
		$this->doParsoidCacheUpdate();

		return true;
	}
}
