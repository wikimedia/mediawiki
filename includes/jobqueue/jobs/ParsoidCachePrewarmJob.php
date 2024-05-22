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
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as ParsoidSiteConfig;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use Psr\Log\LoggerInterface;

/**
 * @ingroup JobQueue
 * @internal
 * @since 1.40
 */
class ParsoidCachePrewarmJob extends Job {
	private LoggerInterface $logger;
	private ParsoidOutputAccess $parsoidOutputAccess;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;
	private ParsoidSiteConfig $parsoidSiteConfig;

	/**
	 * @param array $params
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param PageLookup $pageLookup
	 * @param RevisionLookup $revisionLookup
	 * @param ParsoidSiteConfig $parsoidSiteConfig
	 */
	public function __construct(
		array $params,
		ParsoidOutputAccess $parsoidOutputAccess,
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		ParsoidSiteConfig $parsoidSiteConfig
	) {
		parent::__construct( 'parsoidCachePrewarm', $params );

		// TODO: find a way to inject the logger
		$this->logger = LoggerFactory::getInstance( 'ParsoidCachePrewarmJob' );
		$this->parsoidOutputAccess = $parsoidOutputAccess;
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		$this->parsoidSiteConfig = $parsoidSiteConfig;
	}

	/**
	 * @param int $revisionId
	 * @param PageRecord $page
	 * @param array $params Additional options for the job. Known keys:
	 * - causeAction: Indicate what action caused the job to be scheduled. Used for monitoring.
	 * - options: Flags to be passed to ParsoidOutputAccess:getParserOutput.
	 *   May be set to ParserOutputAccess::OPT_FORCE_PARSE to force a parsing even if there
	 *   already is cached output.
	 *
	 * @return JobSpecification
	 */
	public static function newSpec(
		int $revisionId,
		PageRecord $page,
		array $params = []
	): JobSpecification {
		$pageId = $page->getId();
		$pageTouched = $page->getTouched();

		$params += [ 'options' => 0 ];

		$params += self::newRootJobParams(
			"parsoidCachePrewarm:$pageId:$revisionId:$pageTouched:{$params['options']}"
		);

		$opts = [ 'removeDuplicates' => true ];

		return new JobSpecification(
			'parsoidCachePrewarm',
			[
				'revId' => $revisionId,
				'pageId' => $pageId,
				'page_touched' => $pageTouched,
			] + $params,
			$opts
		);
	}

	private function doParsoidCacheUpdate() {
		$page = $this->pageLookup->getPageById( $this->params['pageId'] );
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

		$rev = $this->revisionLookup->getRevisionById( $revId );
		if ( !$rev ) {
			return;
		}

		$parserOpts = ParserOptions::newFromAnon();

		$renderReason = $this->params['causeAction'] ?? $this->command;
		$parserOpts->setRenderReason( $renderReason );

		$mainSlot = $rev->getSlot( SlotRecord::MAIN );
		if ( !$this->parsoidSiteConfig->supportsContentModel( $mainSlot->getModel() ) ) {
			$this->logger->debug( __METHOD__ . ': Parsoid does not support content model ' . $mainSlot->getModel() );
			return;
		}

		$this->logger->debug( __METHOD__ . ': generating Parsoid output' );

		// We may get the OPT_FORCE_PARSE flag this way
		$options = $this->params['options'] ?? 0;

		// getParserOutput() will write to ParserCache.
		$status = $this->parsoidOutputAccess->getParserOutput( $page, $parserOpts, $rev, $options );

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
